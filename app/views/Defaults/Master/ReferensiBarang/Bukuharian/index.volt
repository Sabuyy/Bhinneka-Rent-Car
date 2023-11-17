{% extends 'template/dashboard.volt' %}
{% block title %}Master Referensi Barang Bukuharian
{% endblock %}
{% block content %}
<style>
	.select2-container--bootstrap4 .select2-selection--single {
		height: calc(3em) !important;
	}
</style>
<div class="page-content" >
	<div class="card ccard mx-auto" style="width: 98%; position: sticky;">
		<div class="card-header pb-1 align-middle border-t-3 brc-primary-tp3" style="border-top-left-radius: 0.4rem;
		border-top-right-radius: 0.4rem;border-bottom: 1px solid #e0e5e8 !important;" >
			<h4 class="card-title text-dark-m3 mt-2">
				Master - Referensi Barang - Kategori
			</h4>
			<div class="page-tools mt-3 mt-sm-0 mb-sm-n1 card-toolbar">

				<button class="btn mr-1 btn-info mb-2 radius-2" data-toggle="modal"
					style="float:right" id="btn-search">
					<i class="fa fa-search text-110 align-text-bottom mr-2"></i>
					Pencarian
				</button>

				<a href="#" class="btn mr-1 btn-success mb-2 radius-2" id="btn-refresh-data">
					<i class="fa fa-sync text-110 align-text-bottom mr-2"></i>
					Perbarui
				</a>
				<a href="#" class="btn mr-1 btn-primary mb-2 radius-2" id="btn-add">
					<i class="fa fa-plus text-110 align-text-bottom mr-2"></i>
					Tambah
				</a>

				<a href="#" class="btn mr-1 btn-warning mb-2 radius-2"id="btn-edit">
					<i class="fa fa-pencil-alt text-140 align-text-bottom mr-2"></i>
					Edit
				</a>
				<a href="#" class="btn mr-1 btn-danger mb-2 radius-2" id="btn-delete">
					<i class="fa fa-trash-alt text-140 align-text-bottom mr-2"></i>
					Hapus
				</a>
				<a href="#" class="btn mr-1 btn-info mb-2 radius-2" id="btn-save">
					<i class="fa fa-save text-140 align-text-bottom mr-2"></i>
					Unduh
				</a>
			</div>
		</div>

		<div class="card-body p-3">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive-md">
						<table id="datatable" class="table table-bordered border-0 w-100 table-striped-secondary text-dark-m1 mb-0">
							<thead>
								<tr class="bgc-info text-white text-center brc-black-tp10">
									<th style="vertical-align: middle;">#</th>
									<th style="vertical-align: middle;">hari</th>
									<th style="vertical-align: middle;">Tanggal</th>
									<th style="vertical-align: middle;">Absen</th>
									<th style="vertical-align: middle;">Judul</th>
									<th style="vertical-align: middle;">Uraian</th>
									<th style="vertical-align: middle;">Lokasi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>

	<!-- Modal Form -->
	<div id="formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
		<div class="modal-dialog radius-4">

			<!-- Modal content-->
			<div class="modal-content radius-4">
				<div class="modal-header bgc-primary radius-t-4">
					<h4 class="modal-title text-white">
						<i class="fa fa-list text-white"></i>&nbsp;&nbsp; Form - Bukuharian</h4>
					<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
				</div>
				<form class="form-horizontal" action="javascript:;">
					<div class="modal-body">
						<input type="hidden" name="_type" value="create">
						<input type="hidden" name="id" value="">
						<div class="row form-group">
                            <div class="col-sm-12" style="margin-bottom : 5px;">
								<div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 150px;">Absen</span>
									</div>
									<select type="text" id="absen" name="absen" class="select2 select2absen" required></select>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sm-12" style="margin-bottom : 5px;">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" style="width: 150px;">Judul</span>
									</div>
									<input type="text" id="judul" name="judul" class="form-control" required>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sm-12" style="margin-bottom : 5px;">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" style="width: 150px;">Uraian</span>
									</div>
									<textarea type="text" id="editor" name="uraian" class="form-control editor" contenteditable="true"	 required></textarea>
									<!-- <div id="div_editor1" class="div_editor1">
										<p>This is a default toolbar demo of Rich text editor.</p>
									</div> -->
								</div>
							</div>
						</div>
						<div class="row form-group">
                            <div class="col-sm-12" style="margin-bottom : 5px;">
								<div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 150px;">Lokasi</span>
									</div>
									<div class="btn-group btn-group-toggle" style="margin-left: 10px;" data-toggle="buttons">
										<label class="btn btn-secondary">
											<input type="radio" name="lokasi" id="lokasi" value="2" checked> WFH
										</label>
										<label class="btn btn-secondary" style="margin-left: 5px;">
											<input type="radio" name="lokasi" id="lokasi" value="1"> WHO
										</label>
									</div>
									<!-- <select type="text" id="lokasi" name="lokasi" class="select2 select2lokasi" required></select> -->
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer  radius-b-4">
						<button type="button" class="btn btn-danger px-4 radius-2" data-dismiss="modal">
							<i class="fas fa-times"></i>
							Tutup
						</button>
						<button type="submit" class="btn btn-success radius-2">
							<i class="fas fa-save"></i>
							Simpan
						</button>
					</div>
				</form>
			</div>

		</div>
	</div>

	<!-- Modal Search -->
	<div id="filterModal" class="modal fade" role="dialog">
		<div class="modal-dialog radius-4">

			<!-- Modal content-->
			<div class="modal-content radius-4">
				<div class="modal-header bgc-primary radius-t-4">
					<h4 class="modal-title text-white">
						<i class="fa fa-search text-white"></i>&nbsp;&nbsp; Pencarian - Bukuharian</h4>
					<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">

					<form class="form-horizontal" id="form-filter">

						<div class="input-group mb-2 input-filter">
							<div class="input-group-prepend" style="width : 10% !important">
								<span class="input-group-text">
									<input type="checkbox" class="ace-switch">
								</span>
							</div>
							<div class="input-group-prepend">
								<span class="input-group-text">
									Judul
								</span>
							</div>
							<input type="text" name="search_judul" class="form-control" disabled="">
						</div>
					</form>
				</div>
				<div class="modal-footer radius-b-4">
					<button type="button" class="btn btn-default submit-filter text-120 radius-2" data-dismiss="modal">Cari Data</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal Search -->
	<div id="SaveModal" class="modal fade" role="dialog">
		<div class="modal-dialog radius-4">

			<!-- Modal content-->
			<div class="modal-content radius-4">
				<div class="modal-header bgc-primary radius-t-4">
					<h4 class="modal-title text-white">
						<i class="fa fa-search text-white"></i>&nbsp;&nbsp; Unduh File - Bukuharian</h4>
					<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" id="form-filter">
						<div class="input-group mb-2 input-filter container">
							<div class="row" style=" width: 120%;">
								<div class="col-12">
										<h2 style=" text-align: center;">Unduh File</h2>
								</div>
							</div>
							<div class="row" style="margin-top: 20px;">
								<!-- <div class="col-1"></div> -->
								<div class="col-6">
									<a href="../../../../../../../../task_fpdf/pdf1/" style="width: 200px;" class="btn mr-1 btn-danger mb-2 radius-2" id="">
										<i class="fa fa-file text-140 align-text-bottom mr-2"></i>
										PDF
									</a>
								</div>
								<div class="col-6">
									<a href="../../../../../../../../phpspreadsheet/index.php" style="width: 200px; " class="btn mr-1 btn-success mb-2 radius-2" id="">
										<i class="fa fa-file text-140 align-text-bottom mr-2"></i>
										Excel
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer radius-b-4">
					<button type="button" class="btn btn-default submit-filter text-120 radius-2" data-dismiss="modal">Cari Data</button>
				</div>
			</div>
		</div>
	</div>
	<!-- <script src="https://cdn.tiny.cloud/1/API-KEY/tinymce/5/tinymce.min.js"></script>
	<script>
  tinymce.init({
    selector: 'textarea#editor',
	api_key: 'vqkscg92py9b6wgx7bfj37w8q8rnif7na6wccnxsv812ajvq'
    });
</script> -->
<!-- <script>
	var editor1 = new RichTextEditor("#div_editor1");
	//editor1.setHTMLCode("Use inline HTML or setHTMLCode to init the default content.");
</script> -->
{% endblock %}
{% block inline_script %}
<script>
	{% include 'Defaults/Master/ReferensiBarang/Bukuharian/index.js' %}
</script>
{% endblock %}

