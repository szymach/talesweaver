var $ = require('jquery');

$(document).ready(function() {
    $('.modal-toggle').on('click', function () {
        $($(this).data('target')).modal();
    });
    $('.modal-load-new-form').on('click', function () {
        getNewForm($(this).parents('.modal').first());
    });
    $('.modal-load-edit-form').on('click', function () {
        var $this = $(this);
        getEditForm($this.parents('.modal').first(), $this.data('edit-form-url'));
    });
    $('.modal-load-list').on('click', function () {
        getList($(this).parents('.modal').first());
    });
});

function getNewForm($modal)
{
    $.ajax({
        method: "GET",
        url: $modal.data('new-form-url'),
        dataType: "json"
    })
    .success(function(response) {
        $modal.find('.modal-body').html(response.form);
        $modal.on('submit', '.js-form', function (event) {
            event.preventDefault();
            event.stopPropagation();
            submitForm($(this), $modal);
        });
    });
}

function getEditForm($modal, url)
{
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json"
    })
    .success(function(response) {
        $modal.find('.modal-body').html(response.form);
        $modal.on('submit', '.js-form', function (event) {
            event.preventDefault();
            event.stopPropagation();
            submitForm($(this), $modal);
        });
    });
}

function submitForm($form, $modal)
{
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        dataType: "json",
        data: $form.serialize()
    })
    .success(function(response) {
        setModalBody($modal, response.form);
        getList($modal);
    });
}

function getList($modal)
{
    $.ajax({
        method: "GET",
        url: $modal.data('list-url'),
        dataType: "json"
    })
    .success(function(response) {
        setModalBody($modal, response.list);
    });
}

function setModalBody($modal, content)
{
    $modal.find('.modal-body').html(content);
}
