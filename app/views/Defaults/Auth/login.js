
$(document).ready(function() {
    select2data()
})
var currentURL = window.location.href;
var parts = currentURL .split("/");
console.log(parts)

// var urlImage = '{{ url("assets") }}/image/'+parts[4]+'.png';
// var urlImage2 = '{{ url("assets") }}/image/fav.png';
// //pdam lain belum ada logonya
// if(parts[4] != "tjm"){
//     $('#fav_icon').attr('href', urlImage2);
//     $('#dashboard_image').attr('src', '{{ url("assets") }}/image/front-logo.svg');
// }
// else{
//     $('#fav_icon').attr('href', urlImage);
//     $('#dashboard_image').attr('src', urlImage);
// }



function select2data(){
    $('.select2pdam').select2({
        allowClear: true,
        theme: "bootstrap4",
        width: '100%',
        ajax: {
            url: "{{ url('panel/referensi/getPdam') }}",
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
                        i.id = i.pdam_id;
                        i.text = i.nama_pdam;
                    
                        return i;
                    }),
                    pagination: {
                        more: data.has_more
                    }
                }
            }
        }
    })
}