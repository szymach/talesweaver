import { ajaxGetCall, ajaxPostCall, offset, scrollTo, trigger } from '../common';
import { AjaxContainer } from './ajaxContainer';
import { Alerts } from './alerts';
import { Display } from './display';
import { Lists } from './lists';
const Gator = require('gator');

interface FormResponse {
    form?: string | null
}

export module Forms {
    export function init(): void {
        Gator(document.querySelector('main, .modal')).on(
            'click',
            '.js-load-form',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                const element: HTMLElement = event.target as HTMLElement;

                Display.closeAllModals();
                getForm(element.getAttribute('data-form-url'));
                scrollTo(offset(AjaxContainer.getContainer()).top, 900);
            }
        );
    }

    export function getForm(url: string): void {
        Lists.closeSublists();
        Lists.closeMobileSublists();

        ajaxGetCall(
            url,
            function (response: FormResponse): void {
                AjaxContainer.displayAjaxContainerWithContent(response.form);
                bindAjaxForm();
                triggerAutofocus();
            }
        );
    }

    function bindAjaxForm(): void {
        const container = AjaxContainer.getContainer();
        Gator(container).off('submit');
        Gator(container).on('submit', '.js-form', (event: Event): boolean => {
            event.preventDefault();
            event.stopPropagation();

            submitForm(<HTMLFormElement>event.target);
            const input: HTMLElement = AjaxContainer.getContainer().querySelector('form input');
            if (null !== input && undefined !== input) {
                trigger(input, 'focus');
            }

            return false;
        });
    }

    function submitForm(form: HTMLFormElement): void {
        const handleErrorCallback = (response: FormResponse): void => {
            if (typeof response.form !== 'undefined' && null !== response.form) {
                AjaxContainer.displayAjaxContainerWithContent(response.form);
            } else {
                AjaxContainer.clearAjaxContainer();
            }

            Alerts.displayAlerts();
        };

        ajaxPostCall(
            form.getAttribute('action'),
            new FormData(form),
            handleErrorCallback,
            handleErrorCallback
        );
    }

    function triggerAutofocus(): void {
        const input: HTMLElement = AjaxContainer.getContainer().querySelector('[autofocus="autofocus"]');

        input.focus();
    }
}
