import { AjaxContainer } from './ajaxContainer';
import { Alerts } from './alerts';
const bootstrap = require('bootstrap.native');
const delegate = require('delegate');
import { addClass, findAncestor, hasClass, ajaxGetCall, removeClass } from '../common';

export module Lists
{
    export function init(): void
    {
        window.addEventListener('resize', () => {
            closeSublists();
            closeMobileSublists();
        });

        delegate(
            document.querySelector('main'),
            '.js-list-toggle',
            'click',
            (event: Event): void => {
                const target: HTMLElement = event.target as HTMLElement;
                const containerWrapper: HTMLElement = findAncestor(target, 'li');
                const container: HTMLElement = containerWrapper.querySelector('.js-list-container');
                const wasOpened: boolean = hasClass(target, 'js-list-toggled');

                if (true === wasOpened) {
                    closeSublists();
                    return;
                }

                AjaxContainer.clearAjaxContainer();
                closeSublists();
                ajaxGetCall(
                    target.getAttribute('data-list-url'),
                    function(): void {
                        const response: { list: string } = this.response;
                        container.innerHTML = response.list;
                        addClass(containerWrapper, 'js-loaded');
                        addClass(target, 'js-list-toggled');
                    }
                );
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-delete',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                const target: HTMLElement = event.target as HTMLElement;
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
        );

        delegate(
            document.querySelector('main'),
            '.js-load-sublist',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                closeSublists();
                closeMobileSublists();
                const target: HTMLElement = event.target as HTMLElement;
                ajaxGetCall(
                    target.getAttribute('data-list-url'),
                    function(): void {
                        const response: { list: string } = this.response;
                        AjaxContainer.clearAjaxContainer();
                        AjaxContainer.displayAjaxContainerWithContent(response.list);
                    }
                );
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-ajax-pagination + .pagination a',
            'click',
            function (event: Event): void {
                event.preventDefault();
                event.stopPropagation();

                const target : HTMLElement = event.target as HTMLElement;
                ajaxGetCall(
                    target.getAttribute('href'),
                    function (): void {
                        const response: { list: string } = this.response;
                        AjaxContainer.clearAjaxContainer();
                        findAncestor(target, '.js-list-container').innerHTML = response.list;
                    }
                );
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-list-action',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                closeSublists();
                const target: HTMLElement = event.target as HTMLElement;
                ajaxGetCall(
                    target.getAttribute('data-action-url'),
                    function (): void {
                        AjaxContainer.clearAjaxContainer();
                        Alerts.displayAlerts();
                    }
                );
            }
        );

        delegate(
            document.querySelector('main'),
            '.js-trigger-sidelist-mobile',
            'click',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                const parent = findAncestor(<HTMLElement>event.target, 'li');
                const wasExpanded: boolean = hasClass(parent, 'mobile-expanded');
                closeSublists();
                closeMobileSublists();
                if (false === wasExpanded) {
                    addClass(parent, 'mobile-expanded');
                }
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
}
