import { AjaxContainer } from './ajaxContainer';
import { Alerts } from './alerts';
import { Display } from './display';
const bootstrap = require('bootstrap.native/dist/bootstrap-native-v4');
const Gator = require('gator');
import { addClass, findAncestor, hasClass, ajaxGetCall, removeClass } from '../common';

interface ListResponse {
    list?: string | null
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
            '.js-list-action',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                performListAction(event.target as HTMLElement);
            }
        );
    }


    function deleteItem(target: HTMLElement): void {
        const modalElement = new bootstrap.Modal(document.getElementById('modal-delete'));
        modalElement.show();
        const clickEvent = (): void => {
            modalElement.hide();
            if (hasClass(target, 'js-list-delete')) {
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

    function performListAction(target: HTMLElement): void {
        ajaxGetCall(
            target.getAttribute('data-action-url'),
            function (): void {
                AjaxContainer.clearAjaxContainer();
                Alerts.displayAlerts();
            }
        );
    }

    function paginateList(target: HTMLElement): void {
        ajaxGetCall(
            target.getAttribute('href'),
            function (response: ListResponse): void {
                AjaxContainer.clearAjaxContainer();
                findAncestor(target, '.js-list-container').innerHTML = response.list;
            }
        );
    }
}
