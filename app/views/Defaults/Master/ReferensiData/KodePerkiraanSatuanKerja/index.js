window.defaultUrl = `${baseUrl}/master/referensi-data/kode-perkiraan-satuan-kerja/`;
var table;
$(document).ready(function() {
    viewDatatable();
    satuankerjaS2();

    var bagianId = $('#satuankerja').val();
    // var response = bagianId?.acc_id || null;
    
    
   

    $('#satuankerja').change(function() {
        $('#message-awal').empty();
        table.ajax.reload()
    });


});
$("#check_all").change(function () {
    var checked = $(this).is(':checked');
    if (checked) {
        $(".listInsert").each(function () {
            $(this).prop("checked", true);
        });
    } else {
        $(".listInsert").each(function () {
            $(this).prop("checked", false);
        });
    }
});


$('#btn_simpanData').click(function (e) {
    e.preventDefault();
    if ($(".listInsert").is(":checked")) {

        var jumlah = $(".cek_status:checked").length;
        var i = 1;
        var bagianId = $('#satuankerja').select2("data")[0];
        // var checkBagianId = bagianId?.of_code || null;
        if (_.isEmpty(bagianId)) {
            notification("danger", "Harap pilih satuan kerja terlebih dahulu");
            return false;
        }

        accId_arr = new Array();
        accIdUncheck_arr = new Array();

        $(".listInsert:checked").each(function () {
            let value = $(this).val();
            let expl = value.split("||");
            accId_arr.push(expl[0]);
            i++;
        });
        $(".listInsert:unchecked").each(function () {
            let value = $(this).val();
            let expl = value.split("||");
            accIdUncheck_arr.push(expl[0]);
            i++;
        });

        var datas_checked = {
            perkiraan_id: accId_arr,
            of_code: $('#satuankerja').val(),
            type: "insertOrUpdate",
        };
        var datas_unchecked = {
            perkiraan_id: accIdUncheck_arr,
            of_code: $('#satuankerja').val(),
            type: "update",
        };

        console.log(datas_checked)
        console.log(datas_unchecked)

        $.ajax({
            url: defaultUrl + `batchInsert`,
            type: "POST",
            data: {datas_checked, datas_unchecked},
            dataType: "JSON",
            beforeSend: function (xhr) {
                $(".loading").removeClass("hide")
            },
            error: function (xhr) {
                $(".loading").removeClass("hide");
            },
            success: function (data) {
                $(".loading").removeClass("hide")
                if (data.error == 0) {
                    notification('success', "Insert data berhasil");
                    window.location.href = defaultUrl;
                } else {
                    notification('danger', "Insert data gagal");
                }
            },
        });

    } else {
        notification("danger", "Centang salah satu atau lebih untuk di simpan !");
    }
});







function viewDatatable() {
    table = $("#datatable").DataTable({
        ajax: {
            url: defaultUrl + "datatable",
            "type": "post",
            "data": function (d) {
                d.satuankerja= $('#satuankerja').val();
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
            data: null,
            orderable: false,
            render: function (data, index, row, meta) {
                // return `
                //     <label>
                //         <input class="listInsert" type="checkbox" name="listInsert[]" value="${data['id']}||${data['nomor_surat']}">
                //     </label>
                // `;
                if (row.is_aktif == 1 && (row.of_code == $('#satuankerja').select2("data")[0]?.of_code || null)) {
                    return `
                    <label class="text-blue text-600 text-bold">
                        <input name="listInsert[]" type="checkbox" value="${data['id']}" class="listInsert ace-switch ace-switch-thin input-lg bgc-blue-d1 text-grey-m2" checked="">
                        <span class="mb-2">
                        </span>
                    </label>`;
                } else {
                    return `
                    <label class="text-blue text-600 text-bold">
                        <input name="listInsert[]" type="checkbox" value="${data['id']}" class="listInsert ace-switch ace-switch-thin input-lg bgc-blue-d1 text-grey-m2">
                        <span class="mb-2">
                        </span>
                    </label>
                    `;
                }
            }
        },
            {
                data: 'id',
                orderable: false,
                render: function (data, index, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + ".";
                },
            },
            {
                data: 'kode'
            },
            {
                data: 'nama'
            },
            {
                data: 'nama_gol'
            },
            {
                data: 'nama_akun_kelompok'
            },
        ],
        "rowCallback": function (row, data, index) {
            if ($(row).hasClass('selected')) {
                $(row).removeClass('selected');
            }
        },
        "createdRow": function (row, data, index) {
            $(row).attr('data-value', encodeURIComponent(JSON.stringify(data)));
            $("thead").css({
                "vertical-align": "middle",
                "text-align": "center",
            });
            $("td", row).css({
                "vertical-align": "middle",
                padding: "0.5em",
                'cursor': 'pointer'
            });
            $("td", row).first().css({
                width: "3%",
                "text-align": "center",
            });
            //Default
            $('td', row).eq(1).css({
                'text-align': 'left',
                'font-weight': 'normal'
            });
            // Disable row click event
            $(row).on('click', function (e) {
                if ($(e.target).is('input[type="checkbox"]')) {
                    // Checkbox clicked, do nothing
                } else {
                    e.stopPropagation();
                }
            });
        }
    });
}


function satuankerjaS2(){
    $('.select2satuankerja').select2({
        allowClear: true,
        theme: "bootstrap4",
        width: 'auto',
        ajax: {
            url: "{{ url('panel/referensi/getSatuanKerja') }}",
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (response) {
                var data = JSON.parse(response);
                console.log(data);
                return {
                    results: data.data.map(function (i) {
                        i.id = i.id;
                        i.text = i.nama;
                    
                        return i;
                    }),
                    pagination: {
                        more: data.has_more
                    }
                }
            }
        }
    }).on('change', function() {
        var selectedValue = $(this).val();
        table.columns(2).search(selectedValue).draw();
    });
}