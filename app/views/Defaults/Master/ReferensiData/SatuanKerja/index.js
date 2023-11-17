window.defaultUrl = `${baseUrl}/master/referensi-data/satuan-kerja/`;
var table ;
$(document).ready(function() {
    viewDatatable();
    
    $('#btn-edit').addClass('disabled');
    $('#btn-delete').addClass('disabled');

    let modal = $('#formModal');
    
    $("#btn-refresh-data").click(function () {
        $('#filterModal').find('input[type=checkbox]').prop("checked", false);
        $('input[name=search_kode]').val('');
        $('input[name=search_kode]').prop('disabled', true);
        $("select[name=search_id_kelompok]").val(false).trigger( "change" );
        $('input[name=search_nama]').val('');
        $('input[name=search_nama]').prop('disabled', true);
        table.ajax.reload();
    });

    $("#btn-search").click(function () {
        select2data();
        $('#filterModal').modal('show');
    });

    $('#btn-add').on('click', function() {
        select2data();
        modal.find('input[name=kode]').val('');
        modal.find('input[name=long_kode]').val('');
        modal.find('input[name=nama]').val('');
        modal.find("select[name=id_kelompok]").val('').trigger('change');
        modal.find('input[name=_type]').val('create');
        resetErrors();
        modal.modal('show');
    });

    $('#btn-edit').on('click', function() {
        let selected = table.row({
            selected: true
        }).data();
        if(_.isEmpty(selected)) {
            notification("warning", "Pilih Data Terlebih Dahulu");
            return false;
        };
        if (selected) {
            select2data();
            console.log(selected);
            modal.find('input[name=_type]').val('edit');
            modal.find('input[name=id]').val(selected.id);
            modal.find('input[name=kode]').val(selected.kode);
            modal.find('input[name=long_kode]').val(selected.long_kode);

            modal.find('input[name=nama').val(selected.nama);
            $("select[name=id_kelompok]").select2("trigger", "select", { data: { id: selected.kelompok_id, text : selected.kelompok} });
            resetErrors();
            modal.modal('show');
        }
    });

    $('#btn-delete').on('click', async function() {
        let selected = table.row({
            selected: true
        }).data();
        if(_.isEmpty(selected)) {
            notification("danger", "Pilih Data Terlebih Dahulu");
            return false;
        };
        if (selected && (await confirmDelete()).value) {
            $.post(defaultUrl + "delete?id=" + selected.id)
                .done(function() {
                    notification('success', "Data berhasil dihapus");
                    table.ajax.reload();
                    $('#btn-edit').addClass('disabled');
                    $('#btn-delete').addClass('disabled');
                })
                .fail(function() {
                    notification('danger', "Data gagal dihapus");
                });
        }
    });

    modal.find('form').on('submit', function(ev) {
        ev.preventDefault();

        let submitButton = $(this).find('[type=submit]');
        let originalContent = submitButton.html();
        submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
        submitButton.prop('disabled', true);

        let type = $('[name=_type]').val();
        let id = $('[name=id]').val();
        let url = type == 'create' ?
            defaultUrl + "create" :
            (defaultUrl + "update");

        resetErrors();
        $.post(url, $(this).serialize())
            .done(function(response) {
                notification('success', "Data berhasil disimpan");
                modal.modal('hide');
                table.ajax.reload();
            })
            .fail(function(jqXHR) {
                if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    let errors = jqXHR.responseJSON.errors;
                    for (let field in errors) {
                        let el = $(`[name=${field}]`);
                        el.toggleClass('brc-danger-m2');
                        el.next().text(errors[field]).show();
                        el.prev().toggleClass('text-danger-d1');
                    }
                }
            })
            .always(function() {
                submitButton.html(originalContent);
                submitButton.prop('disabled', false);
            });
    });
    
});

function viewDatatable(){
    table = $("#datatable").DataTable({
        ajax: {
            url: defaultUrl + "datatable",
            "type": "post",
			"data": function (d) {
				var formData = $("#form-filter").serializeArray();
                $.each(formData, function (key, val) {
					d[val.name] = val.value;
				});
			}
        },
        serverSide: true,
        processing: true,
        responsive: true,
        selected: false,
        aaSorting: [],
        columnDefs: [{
            searchable: false,
            targets: [0]
        }],
        columns: [{
                data: 'id',
                orderable: false,
                render: function(data, index, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + ".";
                },
            },
            {
                data: "kelompok_id",
                render: function (data, type, row, meta) {
                    return row.kelompok
                },
            },
            {
                data: "kode",
                render: function (data, type, row, meta) {
                    if (row.long_kode != null){
                        return '('+row.kode+') - '+row.long_kode
                    }
                    else{
                        return '('+row.kode+') - (-)'
                    }
                    
                },
            },
            {
                data: 'nama'
            }   
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('data-value', encodeURIComponent(JSON.stringify(data)));
            $("thead").css({ "vertical-align": "middle", "text-align": "center", });
            $("td", row).css({ "vertical-align": "middle", padding: "0.5em", 'cursor': 'pointer' });
            $("td", row).first().css({ width: "3%", "text-align": "center", });
            //Default
            $('td', row).eq(1).css({ 'text-align': 'left', 'font-weight': 'normal' });
            
        }

    }).on( 'click', 'tr', function () {
		if ($(this).hasClass('selected')) {
			$('#btn-edit').removeClass("disabled");
			$('#btn-delete').removeClass("disabled");
		} else {
			$('#btn-edit').addClass("disabled");
			$('#btn-delete').addClass("disabled");
        }
	});
}

function resetErrors() {
    $('.form-control').each(function(i, el) {
        $(el).removeClass('brc-danger-m2');
        $(el).next().text('').hide();
        $(el).prev().removeClass('text-danger-d1');
    });
}

function confirmDelete() {
    let swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false,
    });

    return swalWithBootstrapButtons.fire({
        title: 'Apakah anda yakin?',
        type: 'warning',
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        customClass: {
            confirmButton: 'btn btn-success mx-2 px-3 radius-2',
            cancelButton: 'btn btn-danger mx-2 px-3 radius-2'
        }
    });
}

function filterRefresh(){
    table.ajax.reload();
}

function select2data(){
    $('.select2kelompok').select2({
        allowClear: true,
        theme: "bootstrap4",
        width: 'auto',
        ajax: {
            url: "{{ url('panel/referensi/getKelompok') }}",
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (response) {
                var data = JSON.parse(response);

                return {
                    results: data.data.map(function (i) {
                        i.id = i.id;
                        i.text = i.kelompok;
                    
                        return i;
                    }),
                    pagination: {
                        more: data.has_more
                    }
                }
            }
        }
    });
}

