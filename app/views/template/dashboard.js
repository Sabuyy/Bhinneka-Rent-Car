// window.baseUrl = "{{ url() }}";
window.baseUrl = "{{ url() }}{{ module }}/";

$('.dropdown-toggle').dropdown();

$('.date-picker-sql').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true
}).on('input change select', function (e) {
    $(this).valid();
});

$('.date-picker').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true
}).on('input change select', function (e) {
    $(this).valid();
});

$(".yearpicker").datepicker({
    format: "yyyy",
    minViewMode: 'years', // or 1, 月选择
    startView: 'decade', // or 2, 10年选择
    autoclose: true,
}).on('input change select', function (e) {
    $(this).valid();
});;

$('.date-picker-now').datepicker({
    setDate: new Date(),
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true
}).on('input change select', function (e) {
    $(this).valid();
});;

$('.yearmonth-picker').datepicker({
    format: "yyyymm",
    minViewMode: 'months', // or 1, 月选择
    startView: 'decade' // or 2, 10年选择
}).on('changeMonth', function (e) {
    $(e.currentTarget).data('datepicker').hide();
});
$('.timepicker').datetimepicker({
    weekStart: 1,
    todayBtn: 1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 1,
    minView: 0,
    maxView: 1,
    format: "hh:ii:00",
    forceParse: 0
}).on('input change select', function (e) {
    $(this).valid();
});

$('.nominal_uang').priceFormat({
    prefix: '',
    clearPrefix: true,
    centsLimit: 0
});

$('.date-time-picker').datetimepicker({
    format: "dd-mm-yyyy hh:ii:ss",
    autoclose: true,
}).on('input change select', function (e) {
    $(this).valid();
});

$('.date-time-picker-2').datetimepicker({
    format: "yyyy-mm-dd hh:ii:ss",
    autoclose: true,
    stepping: 1
}).on('input change select', function (e) {
    $(this).valid();
});

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};