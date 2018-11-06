import {AjaxContainer} from './ajaxContainer';
import {Alerts} from './alerts';

export module Lists {
    export function init() {
        $(window).on('resize', function () : void {
            closeSublists();
            closeMobileSublists();
        });

        $('main').on('click', '.js-list-toggle', function (event : JQuery.Event) : void {
            const $this : JQuery<HTMLElement> = $(this);
            const $container : JQuery<HTMLElement> = $this.parents('li').first().find('.js-list-container');
            const $containerWrapper = $container.parent();
            const wasOpened : boolean = $this.hasClass('js-list-toggled');

            if (true === $containerWrapper.hasClass('js-loaded')) {
                closeSublists();
            }

            if (true === wasOpened) {
                return;
            }

            AjaxContainer.clearAjaxContainer();
            closeSublists();
            $.ajax({
                method: "GET",
                url: $this.data('list-url'),
                dataType: "json",
                success: function(response : any) : void {
                    $container.html(response.list);
                    $containerWrapper.addClass('js-loaded');
                    $this.addClass('js-list-toggled');
                }
            });
        });

        $('main').on('click', '.js-delete', function (event : JQuery.Event) : void {
            event.preventDefault();
            event.stopPropagation();

            const $this : JQuery<HTMLElement> = $(this);
            $('#modal-delete').modal();
            $('#modal-confirm').off('click').on('click', function() {
                $('#modal-delete').modal('hide');
                if ($this.hasClass('js-list-delete')) {
                    closeSublists();
                    AjaxContainer.clearAjaxContainer();
                    $.ajax({
                        method: "GET",
                        url: $this.data('delete-url'),
                        dataType: "json",
                        success: function() : void {
                            Alerts.displayAlerts();
                        },
                        error: function() : void {
                            Alerts.displayAlerts();
                        }
                    });
                } else {
                    window.location.href = $this.attr('href');
                }
            });
        });

        $('main').on('click', '.js-load-sublist', function (event : JQuery.Event) : void {
            event.preventDefault();
            event.stopPropagation();

            closeSublists();
            closeMobileSublists();
            $.ajax({
                method: "GET",
                url: $(event.currentTarget).data('list-url'),
                dataType: "json",
                success: function(response : any) : void {
                    AjaxContainer.clearAjaxContainer();
                    AjaxContainer.displayAjaxContainerWithContent(response.list);
                }
            });
        });

        $('main').on('click', '.js-ajax-pagination+.pagination a', function (event : JQuery.Event) : void {
            event.preventDefault();
            event.stopPropagation();

            const $this : JQuery<HTMLElement> = $(this);
            $.ajax({
                method: "GET",
                url: $this.attr('href'),
                dataType: "json",
                success: function(response : any) {
                    AjaxContainer.clearAjaxContainer();
                    $this.parents('.js-list-container').html(response.list);
                }
            });
        });

        $('main').on('click', '.js-list-action', function (event : JQuery.Event) : void {
            event.preventDefault();
            event.stopPropagation();

            closeSublists();
            const $this : JQuery<HTMLElement> = $(this);
            $.ajax({
                method: "GET",
                url: $this.data('action-url'),
                dataType: "json",
                success: function() : void {
                    AjaxContainer.clearAjaxContainer();
                    Alerts.displayAlerts();
                }
            });
        });

        $('main').on('click', '.js-trigger-sidelist-mobile', function (event : JQuery.Event) : void {
            event.preventDefault();
            event.stopPropagation();

            const $parent = $(event.currentTarget).parent();
            const wasExpanded : boolean = $parent.hasClass('mobile-expanded');
            closeSublists();
            closeMobileSublists();
            if (false === wasExpanded) {
                $parent.addClass('mobile-expanded');
            }
        });
    }

    export function closeSublists() : void
    {
        const $openedLists : JQuery<HTMLElement> = $('.side-menu .js-loaded');
        if (0 === $openedLists.length) {
            return;
        }

        $openedLists.removeClass('js-loaded').find('.js-list-container').each(function(index, element : HTMLElement) : void {
            $(element).html('');
        });
        $openedLists.find('.js-list-toggled').each(function (index, element : HTMLElement) : void {
            $(element).removeClass('js-list-toggled');
        });
    }

    export function closeMobileSublists() : void
    {
        $('.mobile-expanded').removeClass('mobile-expanded');
    }
}
