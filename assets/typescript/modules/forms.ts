import { ajaxGetCall, ajaxPostCall, offset, scrollTo, trigger, hasClass } from '../common';
import { Alerts } from './alerts';
import { Display } from './display';
import { Lists } from './lists';
const bootstrap = require('bootstrap.native/dist/bootstrap-native-v4');
const Gator = require('gator');
import bsCustomFileInput from 'bs-custom-file-input';

interface FormResponse {
    form?: string | null,
    title?: string | null
}

export module Forms {
    export function init(): void {
        Gator(document.querySelector('main, .modal')).on(
            'click',
            '.js-load-form',
            (event: Event): void => {
                event.preventDefault();

                const element: HTMLElement = event.target as HTMLElement;

                Display.closeAllModals();
                loadForm(element);
                scrollTo(offset(getModal()).top, 900);
            }
        );

        Gator(document.getElementById('modal-form-submit')).on(
            'click',
            (event: Event): void => {
                event.preventDefault();

                submitForm(<HTMLFormElement>getModal().querySelector('.js-form'));
            }
        );
    }

    function loadForm(button: HTMLElement): void {
        ajaxGetCall(
            button.getAttribute('data-form-url'),
            function (response: FormResponse): void {
                const modal = getModal();
                displayModal(modal, response);
                bindAjaxForm(modal);
                triggerAutofocus(modal);
                bsCustomFileInput.init();
            }
        );
    }

    function bindAjaxForm(modal: HTMLElement): void {
        Gator(modal).off('submit');
        Gator(modal).on('submit', '.js-form', (event: Event): boolean => {
            event.preventDefault();
            submitForm(<HTMLFormElement>event.target);

            return false;
        });
    }

    function submitForm(form: HTMLFormElement): void {
        const handleErrorCallback = (response: FormResponse): void => {
            const modal = getModal();
            if (typeof response.form !== 'undefined' && null !== response.form) {
                displayModal(modal, response);
                const input: HTMLElement = modal.querySelector('form input');
                if (null !== input && undefined !== input) {
                    trigger(input, 'focus');
                }
            } else {
                Lists.refreshList(document.querySelector('.tab-content .tab-pane.active .js-ajax-pagination'));
                if (true === hasClass(form, 'js-event-form')) {
                    Lists.refreshList(document.querySelector('#characters .js-ajax-pagination'));
                    Lists.refreshList(document.querySelector('#items .js-ajax-pagination'));
                    Lists.refreshList(document.querySelector('#locations .js-ajax-pagination'));
                }

                clearModal(modal);
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

    function getModal(): HTMLElement {
        return document.getElementById('modal-form');
    }

    function displayModal(modal: HTMLElement, response: FormResponse): void {
        modal.querySelector('.modal-title').innerHTML = response.title;
        modal.querySelector('.modal-body').innerHTML = response.form;
        trigger(modal, 'ckeditor:initialize');
        Display.initScripts();
        new bootstrap.Modal(modal).show()
    }

    function clearModal(modal: HTMLElement): void {
        new bootstrap.Modal(modal).hide();
        Gator(modal).on('hidden.bs.modal', (): void => {
            modal.querySelector('.modal-title').innerHTML = '';
            modal.querySelector('.modal-body').innerHTML = '';
        });
    }

    function triggerAutofocus(modal: HTMLElement): void {
        const input: HTMLElement = modal.querySelector('[autofocus="autofocus"]');

        input.focus();
    }
}
