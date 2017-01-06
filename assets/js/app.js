var $ = require('jquery');

$(document).ready(function() {
    // Turn on modal
    $('.modal-toggle').on('click', function () {
        $($(this).data('target')).modal();
    });
    // Load new form
    $('.modal').on('click', '.modal-load-new-form', function () {
        getNewForm(getModal($(this)));
    });
    // Load edit form
    $('.modal').on('click', '.modal-load-edit-form', function () {
        var $this = $(this);
        var $modal = getModal($this);
        getEditForm($modal, $this.data('edit-form-url'));
    });
    // Load list
    $('.modal').on('click', '.modal-load-list', function () {
        var $modal = getModal($(this));
        getList($modal);
    });
    // Delete row
    $('.modal').on('click', '.modal-delete', function () {
        var $this = $(this);
        if (window.confirm('Na pewno?')) {
            deleteRow($this.data('delete-url'), getModal($this));
        }
    });
    $('.delete').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (window.confirm('Na pewno?')) {
            window.location.href = $(this).attr('href');
        }
    });
    // Back button
    $('#back-button').on('click', function () {
        window.history.back();
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
        hideCreateButton($modal);
        showListButton($modal);
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
        showListButton($modal);
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
        hideListButton($modal);
        showCreateButton($modal);
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

function hideCreateButton($modal)
{
    $modal.find('.modal-load-new-form').first().hide();
}

function showCreateButton($modal)
{
    $modal.find('.modal-load-new-form').first().show();
}

function hideListButton($modal)
{
    $modal.find('.modal-load-list').first().hide();
}

function showListButton($modal)
{
    $modal.find('.modal-load-list').first().show();
}
