var $ = require('jquery');
var savesScheduled = [];

$(document).ready(function() {
    bindAutosave();
});

function bindAutosave() {
    if (typeof CKEDITOR === 'undefined') {
        return;
    }

    for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].on('change', scheduleAutosave);
    }
}

function scheduleAutosave(event)
{
    var $element = $(event.editor.element.$);
    var $form = $element.parents('form');
    var id = $form.attr('id');
    if (savesScheduled[id]) {
        return;
    }
    savesScheduled[id] = true;

    window.setTimeout(function () {
        $.ajax({
            method: "POST",
            url: $form.attr('action'),
            processData: false,
            contentType: false,
            data: new FormData($form[0]),
            success: function() {
                displayAlerts();
            },
            error: function(xhr) {
                var response = JSON.parse(xhr.responseText);
                if (typeof response.form !== 'undefined') {
                    $form.replaceWith($(response.form));
                }
            },
            complete: function () {
                savesScheduled[id] = false;
                bindAutosave();
            }
        });
    }, 3000);
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

function displayAlerts()
{
    $.ajax({
        method: "GET",
        url: $('#alerts').data('alert-url')
    }).success(function (response) {
        if (typeof response.alerts !== 'undefined') {
            $('#alerts').append(response.alerts);
            $('#alerts .alert').filter(':visible').each (function (index, alert) {
                var $alert = $(alert);
                window.setTimeout(function() {
                    $alert.fadeOut(800, function () { $alert.remove(); });
                }, 5000);
            });
        }
    });
}
