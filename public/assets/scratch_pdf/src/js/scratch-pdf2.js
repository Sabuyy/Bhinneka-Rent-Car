class ScratchPDF {

  #pdfDocument = null;
  #pagesAsImageURL = [];
  #serializedCanvas = [];
  #compiledPages = [];

  /** @type number */
  #currentPage = 0;

  /** @type HTMLCanvasElement */
  #canvas = null;
  #fabricCanvas = null;

  #pageChangedCallback = null;

  async #getPageImageURL(pageNumber) {
    if(this.#pagesAsImageURL[pageNumber - 1]) {
      return this.#pagesAsImageURL[pageNumber - 1];
    }

    let page = await this.#pdfDocument.getPage(pageNumber);
    let viewport = page.getViewport(1);

    let requiredScale = this.#fabricCanvas.width / viewport.width;
    viewport = page.getViewport(requiredScale);

    this.#canvas.width = viewport.width;
    this.#canvas.height = viewport.height;

    await page.render({
      canvasContext: this.#canvas.getContext('2d'),
      viewport: viewport,
    });
    
    let blob = await ScratchPDF.canvasToBlob(this.#canvas);
    return this.#pagesAsImageURL[pageNumber - 1] = URL.createObjectURL(blob);
  }

  get pdfDocument() {
    return this.#pdfDocument;
  }

  get canvas() {
    return this.#canvas;
  }

  get fabricCanvas() {
    return this.#fabricCanvas;
  }

  get currentPage() {
    return this.#currentPage;
  }

  /** @returns {number} Total page */
  get totalPage() {
    return this.#pdfDocument.numPages;
  }

  constructor(pdfjsDocument, element) {
    this.#pdfDocument = pdfjsDocument;
    
    this.#fabricCanvas = new fabric.Canvas(element, {
      isDrawingMode: true,
      width: element.getBoundingClientRect().width,
    });

    this.#canvas = document.createElement('canvas');
    this.#initialize();
  }

  async setCurrentPage(pageNumber) {
    if(pageNumber < 1 || this.totalPage < pageNumber || pageNumber == this.currentPage) {
      return false;
    }

    this.#saveState();
    let blobURL = await this.#getPageImageURL(pageNumber);
    fabric.Image.fromURL(blobURL, async (image) => {
      this.#currentPage = pageNumber;
      this.#fabricCanvas.clear();

      if(!(await this.#restoreState())) {
        let requiredScale = this.#fabricCanvas.width / image.width;
        image.scaleX = requiredScale;
        image.scaleY = requiredScale;
        this.#fabricCanvas.setHeight(requiredScale * image.height);
      }

      this.#fabricCanvas.setBackgroundImage(image, () => {
        this.#fabricCanvas.renderAll();
        if(this.#pageChangedCallback !== null) {
          this.#pageChangedCallback(this.#currentPage, this.totalPage);
        }
      });
    });
  }

  #saveState() {
    let pageIndex = this.#currentPage - 1;
    if(pageIndex < 0) return;

    this.#fabricCanvas.setBackgroundImage(null, () => {
      this.#serializedCanvas[pageIndex] = this.#fabricCanvas.toJSON(['width', 'height']);
      let binary = ScratchPDF.b64ToBinary(this.#fabricCanvas.toDataURL());
      this.#compiledPages[pageIndex] = binary;
    });
  }

  #restoreState() {
    return new Promise((resolve) => {
      if(this.#serializedCanvas[this.#currentPage - 1]) {
        let state = this.#serializedCanvas[this.#currentPage - 1];
        this.#fabricCanvas.loadFromJSON(state, () => resolve(true));
      }
      else resolve(false);
    });
  }

  #initialize() {
    this.#pagesAsImageURL = new Array(this.#pdfDocument.numPages);
    this.#pagesAsImageURL.fill(false);
    
    this.#serializedCanvas = new Array(this.#pdfDocument.numPages);
    this.#serializedCanvas.fill(false);
    
    this.#compiledPages = new Array(this.#pdfDocument.numPages);
    this.#compiledPages.fill(false);

    this.#currentPage = 0;
  }

  onPageChanged(callback) {
    if(typeof callback === 'function') {
      this.#pageChangedCallback = callback;
    }
  }

  destroy() {
    for(let index in this.#pagesAsImageURL) {
      let url = this.#pagesAsImageURL[index];
      URL.revokeObjectURL(url);
      delete this.#pagesAsImageURL[index];
      delete this.#serializedCanvas[index];
      delete this.#compiledPages[index];
    }

    this.#currentPage = 0;
  }

  async loadPDF(file) {
    this.destroy();

    if(file instanceof File) {
      file = await file.arrayBuffer();
    }

    if(file instanceof ArrayBuffer || typeof file === 'string' || file instanceof URL) {
      this.#pdfDocument = await PDFJS.getDocument(file);
      
      this.#initialize();
      
      return;
    }

    throw "Could not resolve 'file' argument from loadPDF method";
  }

  async makePDF(params = {
    pdfFile: null,
    pdfName: null,
    uploadURL: null,
    makePDFURL: null,
  }) {
    await this.#saveState();
    let makePDFOptions = {};
    if(params.pdfFile) {
      let arrayBuffer = await params.pdfFile.arrayBuffer();
      let response = await this.#uploadFile(params.uploadURL, new Uint8Array(arrayBuffer), 'application/pdf');
      makePDFOptions.pdf = response.name;
    }
    if(params.pdfName) {
      makePDFOptions.pdf = params.pdfName;
    }

    makePDFOptions.images = [];
    for(let index in this.#compiledPages) {
      if(this.#compiledPages[index]) {
        let arrayBuffer = this.#compiledPages[index];
        let response = await this.#uploadFile(params.uploadURL, arrayBuffer, 'image/png');
        makePDFOptions.images.push({
          page: Number(index) + 1,
          name: response.name,
        });
      }
    }
    
    return await this.#makePdf(params.makePDFURL, makePDFOptions);
  }

  #uploadFile(url, arrayBuffer, contentType = 'application/pdf') {
    return new Promise((resolve, reject) => {
      let xhr = new XMLHttpRequest;
      xhr.open("POST", url, true);
      xhr.setRequestHeader('Content-Type', contentType);
      xhr.onreadystatechange = function() {
        if(xhr.readyState === 4) {
          if(200 <= xhr.status && xhr.status < 300) {
            try {
              resolve(JSON.parse(xhr.response));
            }
            catch {
              resolve(xhr.response);
            }
          }
          else {
            reject();
          }
        }
      }
      xhr.send(arrayBuffer);
    });
  }

  #makePdf(url, data) {
    return new Promise(function (resolve, reject) {
      let xhr = new XMLHttpRequest;
      xhr.open("POST", url, true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onreadystatechange = function() {
        if(this.readyState == 4) {
          if(200 <= this.status && this.status < 300) {
            // console.log(this.response);
            // resolve(this.response);
            var data = JSON.parse(this.response);
            // var filename = data.filename;
            // $("#lampiran_revisi").val(filename);
            resolve(data);
            // console.log(JSON.parse(this.response));
            // return this.response;
          }
          else {
            console.log("salah");
            reject();
          }
        }
      }
     
      // die;
      xhr.send(JSON.stringify(data));
    });
    
  }

  static async load(file, element) {
    if(file instanceof File) {
      file = await file.arrayBuffer();
    }

    if(file instanceof ArrayBuffer || typeof file === 'string' || file instanceof URL) {
      let pdfdoc = await PDFJS.getDocument(file);

      return new ScratchPDF(pdfdoc, element);
    }
    
    throw "Could not resolve 'file' argument from load function";
  }

  static canvasToBlob(canvas) {
    return new Promise((resolve) => {
      canvas.toBlob(function(blob) {
        resolve(blob);
      });
    });
  }

  static b64ToBlob(b64Data, contentType, sliceSize=512) {
    return new Promise((resolve) => {
      const byteCharacters = atob(b64Data);
      const byteArrays = [];

      for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        const slice = byteCharacters.slice(offset, offset + sliceSize);

        const byteNumbers = new Array(slice.length);
        for (let i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i);
        }

        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
      }

      resolve(new Blob(byteArrays, {type: contentType}));
    });
  }

  static b64ToBinary(b64Data) {
      let base64Index = b64Data.indexOf(';base64,') + 8;
      let base64 = b64Data.substring(base64Index);
      let raw = window.atob(base64);
      let rawLength = raw.length;
      let array = new Uint8Array(new ArrayBuffer(rawLength));

      for(let i = 0; i < rawLength; i++) {
        array[i] = raw.charCodeAt(i);
      }

      return array;
  }

  
}