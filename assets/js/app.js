var $ = require('jquery');

$(document).on('ajaxStart', showBackdrop);
$(document).on('ajaxComplete', hideBackdrop);
$(document).on('ajaxError', hideBackdrop);

$(document).ready(function() {
    setAlertFadeOuts();
    closeModal();
    focusFirstInupt();
    focusCkeditor();

    $('main').on('click', '.js-load-form', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var $this = $(this);
        var $listTable = $this.parents('.js-list').first();
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
                    dataType: "json",
                    success: function() {
                        refreshList($this.parents('.js-list'));
                        displayAlerts();
                    },
                    error: function() {
                        displayAlerts();
                    }
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
            dataType: "json",
            success: function(response) {
                $('#modal-display').find('.modal-content').html(response.display);
                $('#modal-display').modal();
            }
        });
    });

    $('main').on('click', '.js-load-sublist', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.data('list-url'),
            dataType: "json",
            success: function(response) {
                clearAjaxContainer();
                displayAjaxContainerWithContent(response.list);
            }
        });
    });

    $('main').on('click', '.js-ajax-pagination a', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.attr('href'),
            dataType: "json",
            success: function(response) {
                var $container = $($this.parents('.js-ajax-pagination').first().data('container'));
                clearAjaxContainer();
                $container.html(response.list);
            }
        });
    });

    $('main').on('click', '.js-list-action', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        $.ajax({
            method: "GET",
            url: $this.data('action-url'),
            dataType: "json",
            success: function() {
                clearAjaxContainer();
                refreshList($($this.data('list-id')));
                displayAlerts();
            }
        });
    });

    getAjaxClearButton().on('click', function() {
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
        dataType: "json",
        success: function(response) {
            displayAjaxContainerWithContent(response.form);
            bindAjaxForm($listTable);
            focusFirstInupt();
        }
    });
}

function refreshList($listTable)
{
    $.ajax({
        method: "GET",
        url: $listTable.data('list-url'),
        dataType: "json",
        success: function(response) {
            $listTable.replaceWith(response.list);
        }
    });
}

function displayAjaxContainerWithContent($content)
{
    var $container = getAjaxContainer();
    clearCKEditor($container);
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
        return false;
    });
}

function submitForm($form, $listTable)
{
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        processData: false,
        contentType: false,
        data: new FormData($form[0]),
        success: function() {
            clearAjaxContainer();
            refreshList($listTable);
            displayAlerts();
        },
        error: function(xhr) {
            clearAjaxContainer();
            var response = JSON.parse(xhr.responseText);
            if (typeof response.form !== 'undefined') {
                displayAjaxContainerWithContent(response.form);
                bindAjaxForm($listTable);
            }
            displayAlerts();
        }
    });
}

function clearAjaxContainer()
{
    var $container = getAjaxContainer();
    getAjaxClearButton().hide();
    clearCKEditor($container);
    $container.html('');
    $container.removeClass('active');
}

function clearCKEditor($container)
{
    var $instance = $container.find('.cke');
    if (!$instance.length) {
        return;
    }

    var instanceId = $instance.attr('id').replace('cke_', '');
    CKEDITOR.instances[instanceId].destroy();
}

function getAjaxContainer()
{
    return $('#ajax-container');
}

function displayAlerts()
{
    $.ajax({
        method: "GET",
        url: $('#alerts').data('alert-url'),
        success: function (response) {
            if (typeof response.alerts !== 'undefined') {
                $('#alerts').append(response.alerts);
                setAlertFadeOuts();
            }
        }
    });
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

function closeModal()
{
    $('.alert .close').on('click', function () {
        $(this).parents('.alert').hide();
    });
}

function setAlertFadeOuts()
{
    $('#alerts .alert').filter(':visible').each (function (index, alert) {
        var $alert = $(alert);
        window.setTimeout(function() {
            $alert.fadeOut(800, function () { $alert.remove(); });
        }, 5000);
    });
}

function focusFirstInupt()
{
    const staticInputs = [
        'form [name="_username"]',
        'form[name="reset_password_request"] [name="reset_password_request[username]"]',
        'form[name="register"] [name="register[username]"]',
        'form[name="create"] [name="create[title]"]',
        'form[name="edit"] [name="edit[title]"]',
        'form[name="create"] [name="create[name]"]',
        'form[name="edit"] [name="edit[name]"]'
    ];

    staticInputs.forEach(function (field) {
        var $input = $(field);
        if ($input.length) {
            $input.trigger('focus');
            return;
        }
    });
}

function focusCkeditor()
{
    if (typeof CKEDITOR === 'undefined') {
        return;
    }

    CKEDITOR.on('instanceReady', function (item) {
        if (true === $(item.editor.element.$).hasClass('ckeditor-focusable')) {
            item.editor.focus();
        }
    });
}
