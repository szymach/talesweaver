const bootstrap = require('bootstrap.native');
const Gator = require('gator');
import { addClass, ajaxGetCall, trigger, removeClass } from '../common';
import { AjaxContainer } from './ajaxContainer';

interface DisplayResponse
{
    display?: string | null
}

export module Display
{
    export function init(): void
    {
        Gator(document.querySelector('main')).on(
            'click',
            '.js-display',
            (event : Event) => {
                const target = event.target as HTMLElement;
                closeAllModals();
                ajaxGetCall(
                    target.getAttribute('data-display-url'),
                    function (): void {
                        const modal : HTMLElement = document.getElementById('modal-display');
                        const response : DisplayResponse = this.response;
                        modal.querySelector('.modal-content').innerHTML = response.display;
                        const modalClass : string = target.getAttribute('data-modal-class');
                        if (null !== modalClass) {
                            addClass(modal.querySelector('.modal-dialog'), modalClass);
                            modal.setAttribute('data-class-to-remove', modalClass);
                        }
                        new bootstrap.Modal(modal).show();
                    }
                );
            }
        );

        const modalDisplay = document.getElementById('modal-display');
        if (null !== modalDisplay) {
            document.getElementById('modal-display')
                .addEventListener('hidden.bs.modal', (event: Event): void => {
                    const target = event.target as HTMLElement;
                    const classToRemove : string = target.getAttribute('data-class-to-remove');
                    if (typeof classToRemove !== 'undefined') {
                        removeClass(target.querySelector('.modal-dialog'), classToRemove);
                        target.removeAttribute('data-class-to-remove');
                    }
                });
        }
    }

    export function closeAllModals(): void
    {
        document.querySelectorAll('.modal.in').forEach(
            (element : Element) => {
                trigger(element.querySelector('[data-dismiss=modal]'), 'click');
            }
        );
        AjaxContainer.clearAjaxContainer();
    }
}
