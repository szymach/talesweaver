var $ = require('jquery');

$(document).ready(function() {
    $('main').on('click', '.side-menu .side-menu-toggle', function () {
        if ($('.side-menu').hasClass('expanded')) {
            $(this).find('.fa').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-left');
        } else {
            $(this).find('.fa').removeClass('fa-arrow-circle-left').addClass('fa-arrow-circle-right');
        }
        $('.side-menu').toggleClass('expanded');
    });
});
