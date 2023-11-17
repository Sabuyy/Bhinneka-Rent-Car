window.defaultUrl = `${baseUrl}/pengaturan/ubah-profile/`;

$(document).ready(function() {
    
    $('#btn-submit').on('click', async function(ev) {
        ev.preventDefault();
        $.ajax({
            type: "POST",
            data: $("#form-data").serializeArray(),
            url: defaultUrl+"updateProfile",
            dataType: "JSON",
            beforeSend: function (xhr) {
                // beforeRequesting(code);
            },
            success: function (data) {
                // $('input[name=password_old]').val('');
                // $('input[name=password_new]').val('');
                // $('input[name=password_confirm]').val('');
                notification("success", 'Profile berhasil di update');
            },
        });
    });
    $('#btn-cancel').on('click', async function(ev) {
        ev.preventDefault();
        $('#foto-profile').attr('src', "{{ url('logo') }}/{{ data.file_logo_pdam }}");
        $('#foto_profile_text').val("{{ data.file_logo_pdam }}");
    });
    $('#foto-profile').attr('src', "{{ url('logo') }}/{{ data.file_logo_pdam }}");
    // $('#elPreview_fotoProfile').show();
});

function fotoUpload(file) {

    var postData = new FormData();
    postData.append('userfile', $('#form-data').find('#foto_profile')[0].files[0]);

    $.ajax({
        url: defaultUrl + "/uploadFoto",
        cache: false,
        contentType: false,
        processData: false,
        data: postData,
        type: 'POST',
        beforeSend: function (data, textStatus, jqXHR) {
            $(".loading").removeClass("hide");
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            let result = JSON.parse(data);
            if (result.status == 0) {
                $(".loading").addClass("hide");
                notification("danger", "Data Gagal Di Upload");
              } else {
                $('#foto_profile_text').val(result.filename);
                $(".loading").addClass("hide");
                notification("success", "Foto Survey Berhasil Di Upload");
                $('#foto-profile').attr('src', "{{ url('logo') }}/" + result.filename);
              }

            // $('#elPreview_fotoProfile').show();
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data);
            console.log(textStatus);
            console.log(jqXHR);

            notification('danger', 'Error: ' + data);
            $(".loading").addClass("hide");
        }
    });
}




