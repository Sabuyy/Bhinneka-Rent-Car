(function () {
    let elements = document.querySelectorAll('#sidebar li.nav-item > a');
    let found = null;
    for (let el of elements) {
        const url = window.location.toString();
        if (url.startsWith(el.href)) {
            if (found == null || found.href.length < el.href.length) {
                found = el;
            }
        }
    }

    function setDeepNavActive(element) {
        let parent = element.parentElement;
        let firstLIFound = false;
        while (parent != null && parent.tagName != 'NAV') {
            if (parent.tagName == 'LI' && parent.classList.contains('nav-item')) {
                parent.classList.add('active');
                if (firstLIFound) {
                    parent.classList.add('open');
                }
                firstLIFound = true;
            }
            if (parent.tagName == 'DIV' && parent.classList.contains('collapse')) {
                parent.classList.add('show');
            }
            parent = parent.parentElement;
        }
    }

    if (found) {
        setDeepNavActive(found);
    }
})();