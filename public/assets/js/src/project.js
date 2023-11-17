// VARIABLE
window.selectedData = "";
window.checkedData = [];
window.table = $('#datatable');
window.hot;


$(document).ready(function () {
    $(".loading").addClass('hide');
    moment.locale("id"); // 'fr'
    $(".disable-form").on("change", function () {
        const form = $(this).parent().parent().next();
        if (this.checked) {
            form.removeAttr("disabled");
        } else {
            form.attr("disabled", "disabled");
        }
    });

    $(".card-toolbar").on("click", ".a, .btn", function (e) {
        e.preventDefault();
        const type = $(this).data("type");

        switch (type) {
            case "refresh-datatable":
                if (typeof refreshData === "function") {
                    refreshData();
                }
                break;
            case "data-add":
                if (typeof openForm === "function") {
                    openForm();
                }
                break;
            case "data-update":
                if (window.selectedData == "") {
                    $.aceToaster.add({
                        placement: 'tr',
                        body: "<div class='bgc-warning-d1 text-white px-3 pt-3'>\
                                    <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                        <i class='fas fa-exclamation-triangle text-150'></i>\
                                    </div>\
                                </div>\
                                <div class='p-3 mb-0 flex-grow-1'>\
                                    <h4 class='text-130'>Perhatian</h4>\
                                    Data belum terpilih\
                                </div>\
                                <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                        width: 320,
                        delay: 3000,
                        className: 'bgc-white-tp1 shadow border-0',

                        bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                        headerClass: 'd-none',
                    })
                } else {
                    if (typeof openForm === "function") {
                        openForm(window.selectedData);
                    }
                }
                break;
            case "data-delete": 
            if (window.selectedData == "") {
                $.aceToaster.add({
                    placement: 'tr',
                    body: "<div class='bgc-warning-d1 text-white px-3 pt-3'>\
                                <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                    <i class='fas fa-exclamation-triangle text-150'></i>\
                                </div>\
                            </div>\
                            <div class='p-3 mb-0 flex-grow-1'>\
                                <h4 class='text-130'>Perhatian</h4>\
                                Data belum terpilih\
                            </div>\
                            <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                    width: 320,
                    delay: 3000,
                    className: 'bgc-white-tp1 shadow border-0',

                    bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                    headerClass: 'd-none',
                })
            } else {
                if (typeof openForm === "function") {
                    $.confirm({
                        title: "Konfirmasi",
                        content: "Anda yakin akan menghapus data ini?",
                        buttons: {
                            Tidak: {
                                btnClass: "btn-warning text-white",
                                action: function () { },
                            },
                            Ya: {
                                btnClass: "btn-primary",
                                action: function () {
                                    var deleteData = selectedData;
                                    selectedData.type = 'delete'
                                    submitData(defaultUrl + "manage", deleteData, "delete");
                                },
                            },
                        },
                    });
                }
            }
                break;
            case "data-rejected":
                if (window.selectedData == "") {
                    $.aceToaster.add({
                        placement: 'tr',
                        body: "<div class='bgc-warning-d1 text-white px-3 pt-3'>\
                                    <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                        <i class='fas fa-exclamation-triangle text-150'></i>\
                                    </div>\
                                </div>\
                                <div class='p-3 mb-0 flex-grow-1'>\
                                    <h4 class='text-130'>Perhatian</h4>\
                                    Data belum terpilih\
                                </div>\
                                <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",
                        width: 320,
                        delay: 3000,
                        className: 'bgc-white-tp1 shadow border-0',
                        bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                        headerClass: 'd-none',
                    })
                } else {
                    if (typeof openForm === "function") {
                        $.confirm({
                            title: "Konfirmasi",
                            content: "Anda yakin akan membatalkan data ini?",
                            buttons: {
                                Tidak: {
                                    btnClass: "btn-warning text-white",
                                    action: function () { },
                                },
                                Ya: {
                                    btnClass: "btn-primary",
                                    action: function () {
                                        var deleteData = selectedData;
                                        selectedData.type = 'rejected'
                                        submitData(defaultUrl + "manage", deleteData, "rejected");
                                    },
                                },
                            },
                        });
                    }
                }
            break;
            case "export-excel-datatable":
                if (typeof exportData === "function") {
                    exportData();
                }
                break;
            case "chart-datatable":
                if (typeof chartData === "function") {
                    chartData();
                }
                break;
            case "filter-datatable":
                if (typeof openFilterForm === "function") {
                    openFilterForm();
                }
                break;
            case "land-detail":
                if (typeof openLandDetail === "function") {
                    openLandDetail();
                }
                break;
            case "close-page":
                close();
                break;
            default:
                break;
        }
    });

    $(".submit-filter").click(function () {
        refreshData();
    })

    $(".check-all").change(function (event) {
        checkedData = [];
        $(".data-checkbox").prop('checked', $(this).prop('checked'));
        $('.data-checkbox:checked').each(function () {
            checkedData.push(this.value);
        });
    })

    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        autoclose: true,
    });

    $('#datatable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        if (!_.isEmpty($(this).data('value'))) {
            window.selectedData = JSON.parse(decodeURIComponent($(this).data('value')));
        }
    });


    // $("#datatable").on("click", ".table-action", function (event) {

    //     const data = JSON.parse(decodeURIComponent($(this).data("value")));
    //     const clickedValue = $(this).data("value");

    //     switch ($(this).data("type")) {
    //         case "detail":
    //             openDetail(data);
    //             break;
    //         case "edit":
    //             openForm(data);
    //             break;
    //         case "delete":
    //             $.confirm({
    //                 title: "Konfirmasi",
    //                 content: "Anda yakin akan menghapus data ini?",
    //                 buttons: {
    //                     Tidak: {
    //                         btnClass: "btn-warning text-white",
    //                         action: function () { },
    //                     },
    //                     Ya: {
    //                         btnClass: "btn-primary",
    //                         action: function () {
    //                             var datas = {
    //                                 id: data.id,
    //                                 type: "delete",
    //                             };
    //                             submitData(defaultUrl + "manage", datas, "delete");
    //                         },
    //                     },
    //                 },
    //             });
    //             break;
    //         default:
    //             break;
    //     }
    // });

    $("#datatable.action-arrow").on('keydown', function (ev) {
        ev.preventDefault();

        let selected = table.rows({ selected: true })[0];
        let tableBody = $('#tabledata_wrapper .dataTables_scrollBody');
        var data = table.row(selected).data();
        if (!!selected) {
            if (ev.key == 'ArrowUp') {
                let prev = table.row(selected[0] - 1);
                if (!!prev[0].length) {
                    prev.select();
                }
                else if (table.page() > 0) {
                    table.page('previous').draw('page');
                    const fn = function () {
                        const rows = table.rows()[0];
                        if (rows.length) {
                            table.row(rows[rows.length - 1]).select();
                        }
                        table.off('draw', fn);
                    }
                    table.on('draw', fn);
                }
            }
            else if (ev.key == 'ArrowDown') {
                let next = table.row(selected[0] + 1);
                if (!!next[0].length) {
                    next.select();
                }
                else if (table.page() < table.page.info().pages - 1) {
                    table.page('next').draw('page');
                    const fn = function () {
                        const rows = table.rows()[0];
                        if (rows.length) {
                            table.row(rows[0]).select();
                        }
                        table.off('draw', fn);
                    }
                    table.on('draw', fn);
                }
            }
            else if (ev.key == 'ArrowLeft' && table.page() > 0) {
                table.page('previous').draw('page');
                const fn = function () {
                    const rows = table.rows()[0];
                    if (rows.length) {
                        table.row(rows[0]).select();
                    }
                    table.off('draw', fn);
                }
                table.on('draw', fn);
            }
            else if (ev.key == 'ArrowRight' && table.page() < table.page.info().pages - 1) {
                table.page('next').draw('page');
                const fn = function () {
                    const rows = table.rows()[0];
                    if (rows.length) {
                        table.row(rows[0]).select();
                    }
                    table.off('draw', fn);
                }
                table.on('draw', fn);
            }
            else if (ev.key == "Enter") {
                if ($("input[name='monitoring_id'][value='" + data['id'] + "']").prop('checked') == false) {
                    $("input[name='monitoring_id'][value='" + data['id'] + "']").prop("checked", true);
                    $("input[name='monitoring_id'][value='" + data['id'] + "']").change(function () {
                        $("input[name='monitoring_id'][value='" + data['id'] + "']").prop('checked') == true;
                    });
                }
            }
            else if (ev.key == "Backspace") {
                if ($("input[name='monitoring_id'][value='" + data['id'] + "']").prop('checked') == true) {
                    $("input[name='monitoring_id'][value='" + data['id'] + "']").prop("checked", false);
                    $("input[name='monitoring_id'][value='" + data['id'] + "']").change(function () {
                        $("input[name='monitoring_id'][value='" + data['id'] + "']").prop('checked') == false;
                    });
                }
            }

        }
        selectedData = data;
    });

    $(".number").inputFilter(function (value) {
        return /^\d*$/.test(value);
    });

    $(".alphabet").inputFilter(function (value) {
        return /^[a-z ]*$/i.test(value);
    });

});

(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };
}(jQuery));


function numberFormat(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

function handsontableSum(col) {
    var sum = 0;
    var values = hot.getDataAtCol(col);
    for (var i = 0; i < hot.countRows(); i++) {
        if (Number.isInteger(parseInt(values[i]))) {
            sum += Math.abs(parseInt(values[i]));
        }

    }
    return sum;
}
function handsontableRowSum(row) {
    var sum = 0;
    var values = hot.getDataAtRow(row);
    for (var i = 0; i < hot.countCols(); i++) {
        if (Number.isInteger(parseInt(values[i])))
            sum += Math.abs(parseInt(values[i]));
    }
    return sum;
}


function handsontableValidate(col1 = 2, col2 = 3) {
    var values1 = hot.getDataAtCol(col1);
    var values2 = hot.getDataAtCol(col2);
    for (var i = 0; i < hot.countRows(); i++) {
        if (values1[i] != 0)
            if (values2[i] == 0)
                return false;

        if (values2[i] != 0)
            if (values1[i] == 0)
                return false;
    }
    return true;
}

function openDetail(data) {
    moment.locale("id");
    $("#modal-detail").modal("show");
}
function openFilterForm() {
    if ($("#collapseExample").hasClass("show")) {
        $("#collapseExample").collapse("hide");
    } else {
        $("#collapseExample").collapse("show");
    }
}

function openForm(data = null) {
    $("#form").trigger("reset");
    $("#form").find("#logo-check").empty();
    $("#modalForm").find("h5.modal-title").text("Form " + modalFormTitle);
    if (data) {
        $.each(data, function (index, value) {
            $("input:text[name=" + index + "]").val(value);
            $("input:password[name=" + index + "]").val(value);
            $(`input[type=email][name="${index}"]`).val(value);
            $("input[type=number][name=" + index + "]").val(value);
            $("textarea[name=" + index + "]").val(value);
            $("select[name=" + index + "]").val(value);
            $(`input:radio[name="${index}"][value="${_.escape(value)}"] `).prop("checked", true);
        });
        $("input[name=id]").val(data.id);       // set ID
        $("input[name=type]").val("update");    // set type form
    } else {
        $("input[name=type]").val("insert");
    }
    $("#modalForm").modal("show");
}

function beforeRequesting(code) {
    $("#overlay").css("display", "block");
}

function onErrorRequest(code, textStatus) {
    $("#overlay").css("display", "none");
    toastr.error(textStatus, "Gagal");
}

function onFinishRequest(code, data) {
    $("#overlay").css("display", "none");
    if (data.error == 0) {
        switch (code) {
            case "default":
                $.aceToaster.add({
                    placement: 'tr',
                    body: "<div class='bgc-success-d1 text-white px-3 pt-3'>\
                                <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                    <i class='fa fa-check text-150'></i>\
                                </div>\
                            </div>\
                            <div class='p-3 mb-0 flex-grow-1'>\
                                <h4 class='text-130'>Berhasil</h4>\
                                "+ data.message + "\
                            </div>\
                            <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                    width: 320,
                    delay: 3000,
                    className: 'bgc-white-tp1 shadow border-0',

                    bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                    headerClass: 'd-none',
                })
                refreshData();
                $("#modalForm").modal("hide");
                break;
            default:
                $.aceToaster.add({
                    placement: 'tr',
                    body: "<div class='bgc-success-d1 text-white px-3 pt-3'>\
                                <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                    <i class='fa fa-check text-150'></i>\
                                </div>\
                            </div>\
                            <div class='p-3 mb-0 flex-grow-1'>\
                                <h4 class='text-130'>Berhasil</h4>\
                                "+ data.message + "\
                            </div>\
                            <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",
                    width: 320,
                    delay: 3000,
                    className: 'bgc-white-tp1 shadow border-0',
                    bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                    headerClass: 'd-none',
                })
                refreshData();
                $("#modalForm").modal("hide");
                break;
        }
    } else {
        if (code == "insert") {
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ data.message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
        } else {
            $("#form").find("#logo-check").empty();
            $("#form").find("#logo-check").append('<i class="fa fa-times"></i>');
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ data.message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
        }
        if (code == "delete") {
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ data.message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
        } else {
            $("#form").find("#logo-check").empty();
            $("#form").find("#logo-check").append('<i class="fa fa-times"></i>');
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ data.message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
        }
        if (code == "update") {
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ data.message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
        } else {
            $("#form").find("#logo-check").empty();
            $("#form").find("#logo-check").append('<i class="fa fa-times"></i>');
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ data.message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
        }
    }
}

function toast(type, message) {

    switch (type) {
        case "success":
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-success-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-check text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Berhasil</h4>\
                            "+ message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;
        case "warning":
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-warning-d1 text-white px-3 pt-3' style='z-index:99999999'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fas fa-exclamation-triangle text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Perhatian</h4>\
                            "+ message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;
        case "danger":
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;

        default:
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-success-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-check text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Berhasil</h4>\
                            Proses Berhasil\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;
    }
}

function replaceImageError() {
    $("img").on("error", function () {
        $(this)
            .attr("src", baseUrl + "images/picture.png")
            .removeClass("w-50")
            .addClass("w-100");
    });
}

function indoDate(date, time = true) {
    var format = time ? "d-D-M-YYYY HH:mm:ss" : "d-D-M-YYYY";
    var days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
    var months = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var formated = moment(date).format(format).split(" ");
    var dated = formated[0].split("-");

    return days[dated[0]] + ", " + dated[1] + " " + months[dated[2]] + " " + dated[3] + (time ? " Pukul " + formated[1] : "");
}

function notification(type, message) {
    switch (type) {
        case "success":
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-success-d1 text-white px-3 pt-3' style='z-index:99999999'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fas fa-check text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Berhasil</h4>\
                            "+ message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;

            break;
        case "warning":
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-warning-d1 text-white px-3 pt-3' style='z-index:99999999'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fas fa-exclamation-triangle text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Perhatian</h4>\
                            "+ message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;
        case "danger":
            $.aceToaster.add({
                placement: 'tr',
                body: "<div class='bgc-danger-d1 text-white px-3 pt-3'>\
                            <div class='border-2 brc-white px-3 py-25 radius-round'>\
                                <i class='fa fa-times text-150'></i>\
                            </div>\
                        </div>\
                        <div class='p-3 mb-0 flex-grow-1'>\
                            <h4 class='text-130'>Gagal</h4>\
                            "+ message + "\
                        </div>\
                        <button data-dismiss='toast' class='align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

                width: 320,
                delay: 3000,
                className: 'bgc-white-tp1 shadow border-0',

                bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
                headerClass: 'd-none',
            })
            break;
        default:
            break;
    }
}

function toRp(angka, type = 1) {
    var rev = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2 = '';
    for (var i = 0; i < rev.length; i++) {
        rev2 += rev[i];
        if ((i + 1) % 3 === 0 && i !== (rev.length - 1)) {
            rev2 += '.';
        }
    }
    if (type == 1) {
        var prefix = " ";
    } else {
        var prefix = " ";
    }
    return prefix + rev2.split('').reverse().join('');
}


$(".rupiah").keyup(function () {
    $(this).val(formatRupiah($(this).val()));
})
$(".rupiah").change(function () {
    $(this).val(formatRupiah($(this).val()));
})

function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function negativeValueRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    td.innerHTML = Math.abs(value)
}