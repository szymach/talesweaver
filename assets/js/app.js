var $ = require('jquery');

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
    });

    $('main').on('click', '.js-delete', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (window.confirm('Na pewno?')) {
            var $this = $(this);
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
                window.location.href = $(this).attr('href');
            }
        }
    });
    $('#clear-form').on('click', function() {
        getFormContainer().html('');
        $('#clear-form').hide();
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
