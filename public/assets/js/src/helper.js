function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function (match) {
        return match.toUpperCase();
    });
}

function rupiah(angka, type = true) {
    let number = Number(angka).toLocaleString('id-ID')
    return (type ? 'Rp. ' : '') + number;
	// var angka = angka || 0;
    // var rev = parseInt(angka, 10).toString().split("").reverse().join("");
    // // console.log(rev);
    // var rev2 = "";
    // for (var i = 0; i < rev.length; i++) {
    //     rev2 += rev[i];
    //     if ((i + 1) % 3 === 0 && i !== rev.length - 1) rev2 += ".";
    // }
    // var prefix = type ? "Rp. " : "";
    // var amount = rev2.split("").reverse().join("");
    // var amount = amount ? amount : 0;
    // // console.log(amount);
    // return prefix + amount;
}

function stringifyTime(data) {
    if (data > 60) {
        data /= 60;
        if (data > 60) {
            data /= 60;
            if (data > 24) {
                data /= 24;
                return "&plusmn" + Math.ceil(data) + " Days";
            }
            return "&plusmn" + Math.ceil(data) + " Hours";
        }
        return "&plusmn" + Math.ceil(data) + " Minutes";
    }
    return "&plusmn" + data + " Seconds";
}

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
}

function deFormatRupiah(string) {
    if (string == "") {
        return string;
    } else {
        var string1 = string.split("Rp").join("");
        var string2 = string1.split(".").join("");
        return string2;
    }
}

/* Fungsi input number */
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;

    return true;
}
