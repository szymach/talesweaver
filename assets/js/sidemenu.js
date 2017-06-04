var $ = require('jquery');

$(document).ready(function() {
    $('main').on('click', '.side-menu .fa', function () {
        $('.side-menu').toggleClass('expanded');
    });
});
