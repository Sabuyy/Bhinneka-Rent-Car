// var _ = require('lodash/core');
$.fn.dataTable.ext.errMode = "none";
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x"></i><h3 style="color:#000000;"></h3>',
        searchPlaceholder: "Cari...",
        sSearch: "",
        lengthMenu: "_MENU_ data/halaman",
        lengthMenu: "_MENU_ data",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Data tidak ditemukan",
        infoFiltered: "",
        zeroRecords: "Data tidak ditemukan",
        emptyTable: "Data tidak ditemukan",
        paginate: {
            first: "Awal",
            previous: "<i class='fa fa-angle-left'></i>",
            next: "<i class='fa fa-angle-right'></i>",
            last: "Akhir",
        },
        select: {
            rows: "%d Baris Dipilih"
        }
    },
    scrollX: true,
    pageLength: 50,
    responsive: true,
    searching: false,
    select: {toggleable: false, 'style': "single" },
    processing: true,
    serverSide: true,
    response: true,
    drawCallback: function (settings) {
        $("i.fa-refresh").removeClass("fa-spin");
    },
    lengthMenu: [
        [10, 20, 30, 50, 100, 200, 300],
        [10, 20, 30, 50, 100, 200, 300],
    ],
    createdRow: function (row, data, index) {
        $(row).attr('data-value', encodeURIComponent(JSON.stringify(data)));
        $("thead").css({
            "vertical-align": "middle",
            "text-align": "center",
        });
        $("td", row).css({
            "vertical-align": "middle",
            "padding": "5px",
            "cursor": "pointer"
        });
    },
    initComplete: function (settings, json) {
        // if (json && json.data && json.data.length > 0)
        //     $('#datatable tbody tr:eq(0)').click();
    }
});

$.validator.setDefaults({
    errorElement: "em",
    onfocusout: function (element) {
        $(element).valid();
    },
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function highlight(element) {
        $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function unhighlight(element) {
        $(element).addClass("is-valid").removeClass("is-invalid");
    },
    submitHandler: function (form) {
        if ($('#nominal').length) {
            $('#nominal').val($('#nominal').unmask());
        }
        var url = $(form).attr("action");
        var data = $(form).serialize();
        var code = $(form).data("code");
        submitData(url, data, code);
        return false;
    },
});

// Page Document

$(document).ready(function () {
    $(".dropdown").click(function () {
        $(".dropdown-menu").toggleClass("show");
    });

    $(".form-check").click(function () {
        var check = $(this).find("input[type='checkbox']:checked").length;
        if (check > 0) {
            $(this).parent().parent().find("select").attr("disabled", false);
            $(this).parent().parent().find("input[type='text']").attr("disabled", false);
            $(this).parent().parent().find("input[type='date']").attr("disabled", false);
            $(this).parent().parent().find("input[type='email']").attr("disabled", false);
            $(this).parent().parent().find(".date-picker").datepicker( "option", "disabled", false );
            $(this).parent().parent().find("input[type='radio']").attr("disabled", false);
        } else {
            $(this).parent().parent().find("select").attr("disabled", true);
            $(this).parent().parent().find("input[type='text']").attr("disabled", true);
            $(this).parent().parent().find("input[type='date']").attr("disabled", true);
            $(this).parent().parent().find("input[type='email']").attr("disabled", true);
            $(this).parent().parent().find("input[type='radio']").attr("disabled", true);
            $(this).parent().parent().find("input[type='text']").val("");
            $(this).parent().parent().find("select").val("").trigger("change");
            $(this).parent().parent().find(".date-picker").datepicker( "option", "disabled", true );
        }
    });

    $(".input-filter").click(function () {
        var check = $(this).find("input[type='checkbox']:checked").length;
        $(this).find("input[type='text']").attr("disabled", (check == 0) ? true : false );
        $(this).find("select").attr("disabled", (check == 0) ? true : false).trigger("change");
        $(this).find("input[type='date']").attr("disabled", (check == 0) ? true : false);
        $(this).find("input[type='email']").attr("disabled", (check == 0) ? true : false);
        $(this).find("input[type='radio']").attr("disabled", (check == 0) ? true : false);
        $(this).find(".date-picker").datepicker( "option", "disabled", (check == 0) ? true : false );
    });

    $(".money").mask("#.##0", {
        reverse: true,
    });
});

//filter

function filterReset() {
    var check = $(".form-check").find("input[type='checkbox']:checked").length;
    if (check > 0) {
        $(".form-check").parent().parent().find("select").attr("disabled", false);
        $(".form-check").parent().parent().find("input[type='text']").attr("disabled", false);
        $(".form-check").parent().parent().find("input[type='file']").attr("disabled", false);
        $(".form-check").parent().parent().find("input[type='date']").attr("disabled", false);
        $(".form-check").parent().parent().find("input[type='email']").attr("disabled", false);
        $(".form-check").parent().parent().find("input[type='radio']").attr("disabled", false);
    } else {
        $(".form-check").parent().parent().find("select").attr("disabled", true);
        $(".form-check").parent().parent().find("input[type='text']").attr("disabled", true);
        $(".form-check").parent().parent().find("input[type='file']").attr("disabled", true);
        $(".form-check").parent().parent().find("input[type='date']").attr("disabled", true);
        $(".form-check").parent().parent().find("input[type='email']").attr("disabled", true);
        $(".form-check").parent().parent().find("input[type='radio']").attr("disabled", true);
    }
}

//Hide Sidebar

function hideSidebar() {
    $("body").removeClass("sidebar-lg-show");
    $(".app-header .navbar-toggler").removeClass("d-lg-none");
    $(".navbar-brand").addClass("d-none");
    $(".navbar-brand").parent().addClass("pl-5");
}

// Table

function refreshData() {
    try {
        table.fnDraw();
    }catch (err) {
        
    }
    
    try {
        table.ajax.reload();
    }catch (err) {
        
    }
        
        
    $("i.fa-refresh").addClass("fa-spin");
    table.$('tr.selected').removeClass('selected');
    selectedData = "";
    checkedData = [];
}

function refreshDatatable(datatableObject) {
    try {
        datatableObject.fnDraw();
        $("i.fa-refresh").addClass("fa-spin");
    } catch (err) {}
}

Array.prototype.remove = function () {
    var what,
        a = arguments,
        L = a.length,
        ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

function uploadFile(target, data, code = "upload") {
    // alert(data);
    // return;
    $.ajax({
        url: target, // point to server-side PHP script
        dataType: "JSON", // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        beforeSend: function (xhr) {
            beforeRequesting(code);
        },
        success: function (data) {
            onFinishRequest(code, data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            onErrorRequest(code, textStatus);
        },
    });
}

// Notif

function showMessage(type, value, autohide = true) {
    var heading = ["Success", "Info", "Failed"];
    var icon = ["success", "info", "error"];
    var background = ["#00c853", "#2962ff", "#d50000"];
    var stack = [3, 3, 1];

    $.toast({
        heading: heading[type],
        text: value,
        showHideTransition: "slide",
        icon: icon[type],
        position: "bottom-right",
        bgColor: background[type],
        textColor: "#FFF",
        loader: false,
        stack: stack[type],
        autohide: autohide,
    });
}

function showAlert(type, message, boolean = false) {
    $("#message-alert").removeAttr("class");
    if (type === "danger") {
        $("#message-alert").addClass("alert alert-danger");
    } else if (type === "success") {
        $("#message-alert").addClass("alert alert-success");
    } else if (type === "warning") {
        $("#message-alert").addClass("alert alert-warning");
    } else if (type === "info") {
        $("#message-alert").addClass("alert alert-info");
    }
    if (boolean) {
        $("#message-alert").html(message);
        $("#message-alert").slideDown();
    } else {
        $("#message-alert").html(message);
        $("#message-alert")
            .slideDown()
            .delay(2000)
            .queue(function (n) {
                $(this).fadeOut();
                n();
            });
    }
}

// Ajax Request

function submitData(url, submitData, code) {
    $.ajax({
        url: url,
        type: "POST",
        data: submitData,
        dataType: "JSON",
        beforeSend: function (xhr) {
            beforeRequesting(code);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            onErrorRequest(code, textStatus);
        },
        success: function (data) {
            onFinishRequest(code, data);
        },
    });
}

function checkPassword(password, callback) {
    $.ajax({
        url: baseUrl + "panel/" + role + "/web/checkpassword",
        type: "POST",
        data: {
            password: password,
        },
        dataType: "json",
        success: function (data) {
            callback(data);
        },
    });
}
function badge(type, text, customClass = "", customStyle = "") {
    return `<span class="badge badge-${type} ${customClass}" style="${customStyle}">${text}</span>`;
}
function linkTo(target, role = true) {
    if (role) {
        return baseUrl + "panel/" + role + "/" + target;
    } else {
        return baseUrl + "panel/" + target;
    }
}
