const bootstrap = require('bootstrap.native/dist/bootstrap-native-v4');
const Gator = require('gator');
import { ajaxGetCall, trigger } from '../common';
import { AjaxContainer } from './ajaxContainer';

interface DisplayResponse {
    display?: string | null
}

export module Display {
    export function init(): void {
        Gator(document.querySelector('main')).on(
            'click',
            '.js-display',
            (event: Event) => {
                event.preventDefault();
                event.stopPropagation();
                const target = event.target as HTMLElement;

                closeAllModals();
                ajaxGetCall(
                    target.getAttribute('data-display-url'),
                    function (response: DisplayResponse): void {
                        const modal: HTMLElement = document.getElementById('modal-display');
                        modal.querySelector('.modal-content').innerHTML = response.display;
                        initScripts();
                        new bootstrap.Modal(modal).show();
                    }
                );
            }
        );

        Gator(document.querySelector('body')).on(
            'click',
            '.js-close-modal',
            () => {
                new bootstrap.Modal(document.getElementById('modal-display')).hide();
            }
        );
    }

    export function closeAllModals(): void {
        document.querySelectorAll('.modal.in').forEach(
            (element: Element) => {
                trigger(element.querySelector('[data-dismiss=modal]'), 'click');
            }
        );
        AjaxContainer.clearAjaxContainer();
    }

    function initScripts() {
        document.querySelectorAll('[data-toggle="tab"]').forEach((element: Element) => {
            new bootstrap.Tab(element);
        });
    }
}
