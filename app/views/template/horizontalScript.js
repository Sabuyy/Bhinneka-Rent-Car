$(document).ready(function () {
    $('#sidebar')
        .on('collapse.ace.sidebar', function () {
            $(this).find('.nav').removeClass('nav-fill text-center')
            $('#id-full-width').prop('checked', false)
        })
        .on('expand.ace.sidebar', function () {
            $(this).find('.nav').addClass('nav-fill text-center')
            $('#id-full-width').prop('checked', true)
        });

    $('#id-navbar-fixed').prop('checked', false)
    $('.navbar').toggleClass('navbar-fixed', false)

    $('#id-full-width')
        .on('change', function () {
            $('.sidebar .nav').toggleClass('nav-fill text-center')
        });

    $('#id-flip-highlight')
        .on('change', function () {
            $('.sidebar .nav').toggleClass('active-on-right')
        });

    $('#id-sm-highlight')
        .on('change', function () {
            $('.sidebar .nav').toggleClass('nav-active-sm')
        });

    $('canvas.task-progress').each(function () {
        var color = $(this).addClass('opacity-2').css('color')

        new Chart(this.getContext('2d'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [$(this).data('percent'), 100 - $(this).data('percent')],
                    backgroundColor: [
                        color,
                        "#e3e5ea"
                    ],
                    hoverBackgroundColor: [
                        color,
                        "#e3e5ea"
                    ],
                    borderWidth: 2
                }]
            },

            options: {
                responsive: false,
                cutoutPercentage: 80,
                legend: {
                    display: false
                },
                animation: {
                    duration: _animate ? 500 : false,
                    easing: 'easeInCubic'
                },
                tooltips: {
                    enabled: false,
                }
            }
        })

    });
});