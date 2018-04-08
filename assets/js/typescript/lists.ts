import * as $ from 'jquery';
import * as ajaxContainer from './ajax-container';
import * as alerts from './alerts';
import * as forms from './forms';

export function refreshList($listTable : JQuery<HTMLElement>)
{
    closeSublists();
    $.ajax({
        method: "GET",
        url: $listTable.data('list-url'),
        dataType: "json",
        success: function(response : any) {
            $listTable.replaceWith(response.list);
        }
    });
}

export function closeSublists()
{
    let $openedLists : JQuery<HTMLElement> = $('.side-menu .loaded');
    if (0 === $openedLists.length) {
        return;
    }

    $openedLists.removeClass('loaded').find('.js-list-container').each(function(index, element : HTMLElement) {
        $(element).html('');
    });
}

$('main').on('click', '.js-list-toggle', function (event : JQuery.Event) {
    let $this : JQuery<HTMLElement> = $(event.currentTarget);
    let $container : JQuery<HTMLElement> = $this.parents('li').first().find('.js-list-container');
    let $containerWrapper = $container.parent();
    if ($containerWrapper.hasClass('loaded')) {
        closeSublists();
    } else {
        ajaxContainer.clearAjaxContainer();
        closeSublists();
        $.ajax({
            method: "GET",
            url: $this.data('list-url'),
            dataType: "json",
            success: function(response : any) {
                $container.html(response.list);
                $containerWrapper.addClass('loaded');
            }
        });
    }
});

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

    closeSublists();
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

$('main').on('click', '.js-ajax-pagination+.pagination a', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    let $this : JQuery<HTMLElement> = $(event.currentTarget);
    $.ajax({
        method: "GET",
        url: $this.attr('href'),
        dataType: "json",
        success: function(response : any) {
            ajaxContainer.clearAjaxContainer();
            $this.parents('.js-list-container').html(response.list);
        }
    });
});

$('main').on('click', '.js-list-action', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    closeSublists();
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
