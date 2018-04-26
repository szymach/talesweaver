import * as $ from 'jquery';
import * as ajaxContainer from './ajax-container';
import {displayAlerts} from './alerts';

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
    let $openedLists : JQuery<HTMLElement> = $('.side-menu .js-loaded');
    if (0 === $openedLists.length) {
        return;
    }

    $openedLists.removeClass('js-loaded').find('.js-list-container').each(function(index, element : HTMLElement) {
        $(element).html('');
    });
    $openedLists.find('.js-list-toggled').each(function (index, element : HTMLElement) {
        $(element).removeClass('js-list-toggled');
    });
}

export function closeMobileSublists()
{
    $('.mobile-expanded').removeClass('mobile-expanded');
}

$(window).on('resize', function () {
    closeSublists();
    closeMobileSublists();
});

$('main').on('click', '.js-list-toggle', function (event : JQuery.Event) {
    const $this : JQuery<HTMLElement> = $(event.currentTarget);
    const $container : JQuery<HTMLElement> = $this.parents('li').first().find('.js-list-container');
    const $containerWrapper = $container.parent();

    const wasOpened : boolean = $this.hasClass('js-list-toggled');

    if (true === $containerWrapper.hasClass('js-loaded')) {
        closeSublists();
    }

    if (true === wasOpened) {
        return;
    }

    ajaxContainer.clearAjaxContainer();
    closeSublists();
    $.ajax({
        method: "GET",
        url: $this.data('list-url'),
        dataType: "json",
        success: function(response : any) {
            $container.html(response.list);
            $containerWrapper.addClass('js-loaded');
            $this.addClass('js-list-toggled');
        }
    });
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

$('main').on('click', '.js-load-sublist', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    closeSublists();
    closeMobileSublists();
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
            displayAlerts();
        }
    });
});

$('main').on('click', '.js-trigger-sidelist-mobile', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    const $this = $(event.currentTarget);
    const wasExpanded : boolean = $this.hasClass('mobile-expanded');
    closeSublists();
    closeMobileSublists();
    if (false === wasExpanded) {
        $this.addClass('mobile-expanded');
    }
});