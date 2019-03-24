const bootstrap = require('bootstrap.native');
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
                const target = event.target as HTMLElement;
                closeAllModals();
                ajaxGetCall(
                    target.getAttribute('data-display-url'),
                    function (): void {
                        const modal: HTMLElement = document.getElementById('modal-display');
                        const response: DisplayResponse = this.response;
                        modal.querySelector('.modal-content').innerHTML = response.display;
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
}
