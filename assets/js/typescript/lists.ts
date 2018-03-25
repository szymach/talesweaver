import * as $ from 'jquery';
import * as ajaxContainer from './ajax-container';
import * as alerts from './alerts';

export function refreshList($listTable : JQuery<HTMLElement>)
{
    $.ajax({
        method: "GET",
        url: $listTable.data('list-url'),
        dataType: "json",
        success: function(response : any) {
            $listTable.replaceWith(response.list);
        }
    });
}

$('main').on('click', '.js-delete', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    let $this : JQuery<HTMLElement> = $(event.currentTarget);
    $('#modal-delete').modal();
    $('#modal-confirm').off('click').on('click', function() {
        $('#modal-delete').modal('hide');
        if ($this.hasClass('js-list-delete')) {
            ajaxContainer.clearAjaxContainer();
            $.ajax({
                method: "GET",
                url: $this.data('delete-url'),
                dataType: "json",
                success: function() {
                    refreshList($this.parents('.js-list'));
                    alerts.displayAlerts();
                },
                error: function() {
                    alerts.displayAlerts();
                }
            });
        } else {
            window.location.href = $this.attr('href');
        }
    });
});

$('main').on('click', '.js-load-sublist', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    $.ajax({
        method: "GET",
        url: $(event.currentTarget).data('list-url'),
        dataType: "json",
        success: function(response : any) {
            ajaxContainer.clearAjaxContainer();
            ajaxContainer.displayAjaxContainerWithContent(response.list);
        }
    });
});

$('main').on('click', '.js-ajax-pagination .pagination a', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    let $this : JQuery<HTMLElement> = $(event.currentTarget);
    $.ajax({
        method: "GET",
        url: $this.attr('href'),
        dataType: "json",
        success: function(response : any) {
            let $container = $this.parents('.js-ajax-pagination').first();
            ajaxContainer.clearAjaxContainer();
            $container.replaceWith(response.list);
        }
    });
});

$('main').on('click', '.js-list-action', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    let $this : JQuery<HTMLElement> = $(event.currentTarget);
    $.ajax({
        method: "GET",
        url: $this.data('action-url'),
        dataType: "json",
        success: function() {
            ajaxContainer.clearAjaxContainer();
            refreshList($($this.data('list-id')));
            alerts.displayAlerts();
        }
    });
});
