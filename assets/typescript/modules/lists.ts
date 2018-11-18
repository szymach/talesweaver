import { AjaxContainer } from './ajaxContainer';
import { Alerts } from './alerts';
const bootstrap = require('bootstrap.native');
const delegate = require('delegate');
import { addClass, findAncestor, hasClass, ajaxGetCall, removeClass } from '../common';

interface ListResponse
{
    list?: string | null
}

export module Lists
{
    export function init(): void
    {
        window.addEventListener('resize', (): void => {
            closeSublists();
            closeMobileSublists();
        });

        delegate(
            document.querySelector('main'),
            '.js-list-toggle',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                openSublist(event.target as HTMLElement);
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-delete',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                deleteItem(event.target as HTMLElement);
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-load-sublist',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                loadSublist(event.target as HTMLElement);
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-ajax-pagination + .pagination a',
            'click',
            function (event: Event): void {
                event.preventDefault();
                event.stopPropagation();

                paginateList(event.target as HTMLElement);
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-list-action',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                performListAction(event.target as HTMLElement);
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-trigger-sidelist-mobile',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                triggerMobileList(findAncestor(<HTMLElement>event.target, 'li'));
            }
        );
    }

    export function closeSublists(): void
    {
        const openedLists = document.querySelectorAll('.side-menu .js-loaded');
        if (0 === openedLists.length) {
            return;
        }

        openedLists.forEach((item : Element): void => {
            removeClass(<HTMLElement>item, 'js-loaded');
            item.querySelectorAll('.js-list-container')
                .forEach((container: Element): void => {
                    container.innerHTML = '';
                }
            );
            item.querySelectorAll('.js-list-toggled')
                .forEach((list: Element): void => {
                    removeClass(<HTMLElement>list, 'js-list-toggled');
                }
            );
        });
    }

    export function closeMobileSublists(): void
    {
        document.querySelectorAll('.mobile-expanded')
                .forEach((element: Element): void => {
                    removeClass(<HTMLElement>element, 'mobile-expanded');
                })
        ;
    }

    function openSublist(target: HTMLElement): void
    {
        if (true === hasClass(target, 'js-list-toggled')) {
            closeSublists();
            return;
        }

        closeSublists();
        const containerWrapper: HTMLElement = findAncestor(target, 'li');
        const container: HTMLElement = containerWrapper.querySelector('.js-list-container');
        AjaxContainer.clearAjaxContainer();
        ajaxGetCall(
            target.getAttribute('data-list-url'),
            function(): void {
                const response: ListResponse = this.response;
                container.innerHTML = response.list;
                addClass(containerWrapper, 'js-loaded');
                addClass(target, 'js-list-toggled');
            }
        );
    }

    function deleteItem(target: HTMLElement): void
    {
        const modalElement = new bootstrap.Modal(document.getElementById('modal-delete'));
        modalElement.show();
        const clickEvent = (): void => {
            modalElement.hide();
            if (hasClass(target, 'js-list-delete')) {
                closeSublists();
                AjaxContainer.clearAjaxContainer();
                ajaxGetCall(
                    target.getAttribute('data-delete-url'),
                    (): void => {
                        Alerts.displayAlerts();
                    },
                    (): void => {
                        Alerts.displayAlerts();
                    }
                );
            } else {
                window.location.href = target.getAttribute('href');
            }
        };

        const modalConfirm: HTMLElement = document.getElementById('modal-confirm');
        modalConfirm.removeEventListener('click', clickEvent);
        modalConfirm.addEventListener('click', clickEvent);
    }

    function loadSublist(target: HTMLElement): void
    {
        closeSublists();
        closeMobileSublists();
        ajaxGetCall(
            target.getAttribute('data-list-url'),
            function(): void {
                const response: ListResponse = this.response;
                AjaxContainer.clearAjaxContainer();
                AjaxContainer.displayAjaxContainerWithContent(response.list);
            }
        );
    }

    function triggerMobileList(parent: HTMLElement): void
    {
        const wasExpanded: boolean = hasClass(parent, 'mobile-expanded');
        closeSublists();
        closeMobileSublists();
        if (false === wasExpanded) {
            addClass(parent, 'mobile-expanded');
        }
    }

    function performListAction(target: HTMLElement): void
    {
        closeSublists();
        ajaxGetCall(
            target.getAttribute('data-action-url'),
            function (): void {
                AjaxContainer.clearAjaxContainer();
                Alerts.displayAlerts();
            }
        );
    }

    function paginateList(target: HTMLElement): void
    {
        ajaxGetCall(
            target.getAttribute('href'),
            function (): void {
                const response: ListResponse = this.response;
                AjaxContainer.clearAjaxContainer();
                findAncestor(target, '.js-list-container').innerHTML = response.list;
            }
        );
    }
}
