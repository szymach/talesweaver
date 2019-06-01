import { Alerts } from './alerts';
import { ajaxGetCall, findAncestor, hasClass } from '../common';
const bootstrap = require('bootstrap.native/dist/bootstrap-native-v4');
const Gator = require('gator');

interface ListResponse {
    list?: string | null,
    title?: string | null
}

export module Lists {
    export function init(): void {
        Gator(document.querySelector('main')).on(
            'click',
            '.js-delete',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                deleteItem(event.target as HTMLElement);
            }
        );

        Gator(document.querySelector('main')).on(
            'click',
            '.js-ajax-pagination + .pagination a',
            function (event: Event): void {
                event.preventDefault();
                event.stopPropagation();

                paginateList(event.target as HTMLElement);
            }
        );

        Gator(document.querySelector('main')).on(
            'click',
            '.js-load-sublist',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                loadSublist(event.target as HTMLElement);
            }
        );

        Gator(document.querySelector('body')).on(
            'click',
            '.js-list-action',
            (event: Event): void => {
                event.preventDefault();

                performListAction(event.target as HTMLElement);
            }
        );
    }

    export function refreshList(target: HTMLElement): void {
        if (false === hasClass(target, 'js-ajax-pagination')) {
            target = findAncestor(target, '.js-ajax-pagination');
        }

        ajaxGetCall(
            target.getAttribute('data-list-url'),
            function (response: ListResponse): void {
                findAncestor(target, '.js-list-container').innerHTML = response.list;
            }
        );
    }

    function deleteItem(target: HTMLElement): void {
        const modalElement = new bootstrap.Modal(document.getElementById('modal-delete'));
        const clickEvent = (): void => {
            modalElement.hide();
            if (hasClass(target, 'js-list-delete')) {
                ajaxGetCall(
                    target.getAttribute('data-delete-url'),
                    (): void => {
                        refreshList(target);
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

        modalElement.show();
    }

    function loadSublist(target: HTMLElement): void {
        const modal = getModal();
        ajaxGetCall(
            target.getAttribute('data-list-url'),
            function (response: ListResponse): void {
                modal.querySelector('.modal-title').innerHTML = response.title;
                modal.querySelector('.modal-body').innerHTML = response.list;

                new bootstrap.Modal(modal).show();
            }
        );
    }

    function performListAction(target: HTMLElement): void {
        ajaxGetCall(
            target.getAttribute('data-action-url'),
            (): void => {
                refreshList(
                    document.querySelector('.js-list-container.active .js-ajax-pagination') as HTMLElement
                );
                Alerts.displayAlerts();
                new bootstrap.Modal(getModal()).hide();
            },
            (): void => {
                Alerts.displayAlerts();
            }
        );
    }

    function paginateList(target: HTMLElement): void {
        ajaxGetCall(
            target.getAttribute('href'),
            function (response: ListResponse): void {
                findAncestor(target, '.js-list-container').innerHTML = response.list;
            }
        );
    }

    function getModal(): HTMLElement {
        return document.getElementById('modal-list');
    }
}
