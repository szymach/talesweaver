var $ = require('jquery');

$(document).ready(function() {
    $('.form-container').each(function (index, element) {
        var $formContainer = $(element);
        getForm($formContainer);
        $formContainer.on('submit', '.js-form', function (event) {
            event.preventDefault();
            event.stopPropagation();
            submitForm($(this));
        });
    });
});

function submitForm($form)
{
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        dataType: "json",
        data: $form.serialize()
    })
    .success(function(response) {
        $(getFormContainer($form)).html(response.form);
        updateList($(getListContainer($form)));
    });
}

function getForm($formContainer)
{
    $.ajax({
        method: "GET",
        url: $formContainer.data('form-url'),
        dataType: "json"
    })
    .success(function(response) {
        $formContainer.html(response.form);
    });
}

function updateList($listContainer)
{
    $.ajax({
        method: "GET",
        url: $listContainer.data('list-url'),
        dataType: "json"
    })
    .success(function(response) {
        $listContainer.html(response.list);
    });
}

function getFormContainer($form)
{
    return '.form-container.' + $form.data('container-id');
}

function getListContainer($form)
{
    return '.list-container.' + $form.data('container-id');
}