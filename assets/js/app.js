var $ = require('jquery');

$(document).on('ajaxStart', function() {
    showBackdrop();
}).on('ajaxComplete', function() {
    hideBackdrop();
});

$(document).ready(function() {
    $('main').on('click', '.js-load-form', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var $this = $(this);
        var $listTable = $this.parents('table').first();
        var url = $this.hasClass('js-edit-form')
            ? $this.data('form-url')
            : $listTable.data('form-url')
        ;
        getForm(url, $listTable);
        $('#clear-form').show();
        $('html, body').animate({
            scrollTop: $("#clear-form").offset().top
        }, 2000);
    });

    $('main').on('click', '.js-delete', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $('#modal-delete').modal();
        $('#modal-confirm').unbind('click').on('click', function() {
            $('#modal-delete').modal('hide');
            if ($this.hasClass('js-list-delete')) {
                $.ajax({
                    method: "GET",
                    url: $this.data('delete-url'),
                    dataType: "json"
                })
                .success(function(response) {
                    $this.parents('table').first().html(response.list);
                });
            } else {
                window.location.href = $this.attr('href');
            }
        });
    });

    $('main').on('click', '.js-display', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.data('display-url'),
            dataType: "json"
        })
        .success(function(response) {
            $('#modal-display').find('.modal-content').html(response.display);
            $('#modal-display').modal();
        });
    });

    $('#clear-form').on('click', function() {
        showBackdrop();
        $('html, body').animate({ scrollTop: $("main").offset().top }, 500, function () {
            getFormContainer().html('');
            $('#clear-form').hide();
            hideBackdrop();
        });
    });
});

function getForm(url, $listTable)
{
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json"
    })
    .success(function(response) {
        var $container = getFormContainer();
        $container.html(response.form);
        $container.unbind('submit');
        $container.on('submit', '.js-form', function (event) {
            event.preventDefault();
            event.stopPropagation();
            submitForm($(this), $container, $listTable);
        });
        var $input = $container.find('form input').first();
        if ($input.length) {
            $input.focus();
        }
    });
}

function submitForm($form, $container, $listTable)
{
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        dataType: "json",
        data: $form.serialize()
    })
    .success(function() {
        $container.html('');
        refreshList($listTable);
    })
    .error(function(response) {
        $container.html(response.form);
    });
}

function refreshList($listTable)
{
    $.ajax({
        method: "GET",
        url: $listTable.data('list-url'),
        dataType: "json"
    })
    .success(function(response) {
        $listTable.html(response.list);
    });
}

function getFormContainer()
{
    return $('#form-container');
}

function showBackdrop()
{
    $('html').css('cursor', 'wait');
    $('#backdrop').addClass('active');
}

function hideBackdrop()
{
    $('html').css('cursor', 'default');
    $('#backdrop').removeClass('active');
}
