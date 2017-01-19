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
        $('html, body').animate({
            scrollTop: $("#clear-ajax").offset().top
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
                clearAjaxContainer();
                $.ajax({
                    method: "GET",
                    url: $this.data('delete-url'),
                    dataType: "json"
                })
                .success(function(response) {
                    var $container = $($this.parents('table').first().data('container'));
                    $container.html(response.list);
                    displaySuccessAlert();
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

    $('main').on('click', '.js-load-sublist', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.data('list-url'),
            dataType: "json"
        })
        .success(function(response) {
            clearAjaxContainer();
            displayAjaxContainerWithContent(response.list);
        });
    });

    $('main').on('click', '.js-ajax-pagination a', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.attr('href'),
            dataType: "json"
        })
        .success(function(response) {
            var $container = $($this.parents('.js-ajax-pagination').first().data('container'));
            clearAjaxContainer();
            $container.html(response.list);
        });
    });

    $('main').on('click', '.js-list-action', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.data('action-url'),
            dataType: "json"
        })
        .success(function(response) {
            clearAjaxContainer();
            $($this.data('list-id')).html(response.list);
            displaySuccessAlert();
        });
    });

    $('#clear-ajax').on('click', function() {
        showBackdrop();
        $('html, body').animate({ scrollTop: $("main").offset().top }, 500, function () {
            clearAjaxContainer();
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
        displayAjaxContainerWithContent(response.form);
        bindAjaxForm($listTable);
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
        var $container = $($listTable.data('container'));
        $container.html(response.list);
    });
}

function displayAjaxContainerWithContent($content)
{
    var $container = getAjaxContainer();
    $container.html($content);
    getAjaxClearButton().show();
    $container.addClass('active');
}

function bindAjaxForm($listTable)
{
    var $container = getAjaxContainer();
    $container.unbind('submit');
    $container.on('submit', '.js-form', function (event) {
        event.preventDefault();
        event.stopPropagation();
        submitForm($(this), $listTable);
        var $input = $container.find('form input').first();
        if ($input.length) {
            $input.focus();
        }
    });
}

function submitForm($form, $listTable)
{
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        processData: false,
        contentType: false,
        data: new FormData($form[0])
    })
    .success(function() {
        clearAjaxContainer();
        refreshList($listTable);
        displaySuccessAlert();
    })
    .error(function(xhr) {
        clearAjaxContainer();
        var response = JSON.parse(xhr.responseText);
        if (typeof response.form !== 'undefined') {
            displayAjaxContainerWithContent(response.form);
            bindAjaxForm($listTable);
        }
        displayErrorAlert();
    });
}

function clearAjaxContainer()
{
    var $container = getAjaxContainer();
    getAjaxClearButton().hide();
    $container.html('');
    $container.removeClass('active');
}

function getAjaxContainer()
{
    return $('#ajax-container');
}

function displaySuccessAlert()
{
    $('#error-alert').hide();
    var $alert = $('#success-alert');
    $alert.show();
    window.setTimeout(function() { $alert.fadeOut(800, function () { $alert.alert('close') }); }, 5000);
}

function displayErrorAlert()
{
    $('#error-alert').show();
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

function getAjaxClearButton()
{
    return $('#clear-ajax');
}
