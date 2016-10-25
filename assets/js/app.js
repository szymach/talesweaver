var $ = require('jquery');

$(document).ready(function() {
    // Turn on modal
    $('.modal-toggle').on('click', function () {
        $($(this).data('target')).modal();
    });
    // Load new form
    $('.modal-load-new-form').on('click', function () {
        getNewForm(getModal($(this)));
    });
    // Load edit form
    $('.modal-load-edit-form').on('click', function () {
        var $this = $(this);
        getEditForm(getModal($this), $this.data('edit-form-url'));
    });
    // Load list
    $('.modal-load-list').on('click', function () {
        getList(getModal($(this)));
    });
    // Delete row
    $('.modal-delete').on('click', function () {
        var $this = $(this);
        deleteRow($this.data('delete-url'), getModal($this));
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

function deleteRow(url, $modal)
{
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json"
    })
    .success(function(response) {
        setModalBody($modal, response.list);
    });
}

function getModal($element)
{
    return $element.parents('.modal').first();
}

function setModalBody($modal, content)
{
    $modal.find('.modal-body').html(content);
}
