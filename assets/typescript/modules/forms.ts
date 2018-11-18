import { ajaxGetCall, ajaxPostCall, offset, scrollTo, trigger } from '../common';
import { AjaxContainer } from './ajaxContainer';
import { Alerts } from './alerts';
const delegate = require('delegate');
import { Display } from './display';
import { Lists } from './lists';

interface FormResponse
{
    form?: string | null
}

export module Forms
{
    export function init(): void
    {
        delegate(
            document.querySelector('main, .modal'),
            '.js-load-form',
            'click',
            (event: Event): void => {
                const element: HTMLElement = event.target as HTMLElement;
                if (false === element instanceof HTMLElement) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                Display.closeAllModals();

                getForm(element.getAttribute('data-form-url'));
                scrollTo(offset(AjaxContainer.getClearAjaxButton()).top, 900);
            }
        );
    }

    export function getForm(url : string): void
    {
        Lists.closeSublists();
        Lists.closeMobileSublists();

        ajaxGetCall(
            url,
            function(): void {
                const response: FormResponse = this.response;
                AjaxContainer.displayAjaxContainerWithContent(response.form);
                bindAjaxForm();
                triggerAutofocus();
            }
        );
    }

    function bindAjaxForm(): void
    {
        const submitCallback = (event: Event) => {
            event.preventDefault();
            event.stopPropagation();

            submitForm(<HTMLFormElement>event.target);
            const input: HTMLElement = AjaxContainer.getContainer().querySelector('form input');
            if (null !== input) {
                trigger(input, 'focus');
            }

            return false;
        };

        AjaxContainer.getContainer().removeEventListener('submit', submitCallback);
        delegate(AjaxContainer.getContainer(), '.js-form', 'submit', submitCallback);
    }

    function submitForm(form: HTMLFormElement): void
    {
        ajaxPostCall(
            form.getAttribute('action'),
            new FormData(form),
            (): void => {
                AjaxContainer.clearAjaxContainer();
                Alerts.displayAlerts();
            },
            (xhr: {responseText : string}) => {
                AjaxContainer.clearAjaxContainer();
                const response : FormResponse = JSON.parse(xhr.responseText);
                if (null !== response.form) {
                    AjaxContainer.displayAjaxContainerWithContent(response.form);
                }
                Alerts.displayAlerts();
            }
        );
    }

    function triggerAutofocus(): void
    {
        const input: HTMLElement = AjaxContainer.getContainer().querySelector('[autofocus="autofocus"]');
        if (null === input) {
            return;
        }

        trigger(input, 'focus');
    }
}
