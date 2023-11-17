window.defaultUrl = `${baseUrl}/pengaturan/ubah-password/`;


$(document).ready(function() {
    let modal = $('#formModal')

    $('#btn-submit').on('click', async function(ev) {
        ev.preventDefault();

        const isPasswordValid = await checkPassword();

        if (!isPasswordValid) {
            notification('danger', "Password Lama Salah");
            return false;
        }

        if ( ! checkNewPassword()) {
            notification('warning', "Konfirmasi Password Tidak Tepat");
            return false
        } 

        $.ajax({
            type: "POST",
            data: $("#form-data").serializeArray(),
            url: defaultUrl+"setPassword",
            dataType: "JSON",
            beforeSend: function (xhr) {
                // beforeRequesting(code);
            },
            success: function (data) {
                $('input[name=password_old]').val('');
                $('input[name=password_new]').val('');
                $('input[name=password_confirm]').val('');
                notification("success", 'Password berhasil diubah');
            },
        });
    });

});


function checkPassword() {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: defaultUrl + 'getPassword',
            type: 'POST',
            data: {
                oldPassword: $('input[name=password_old]').val()
            },
            success: function(response) {
                const isValid = response === true;
                resolve(isValid);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}

function checkNewPassword() {
    var newPassword = $('input[name=password_new]').val();
    var newPasswordConfirm = $('input[name=password_confirm]').val();

    if (newPassword != newPasswordConfirm) {
        return false;
    } else {
        return true;
    }
}



