var $ = require('jquery');

$(document).ready(function() {
    $('main').on('click', '.side-menu', function () {
        $('.side-menu').toggleClass('expanded');
    });
});
