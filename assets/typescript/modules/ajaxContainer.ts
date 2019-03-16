import { addClass, removeClass, trigger } from '../common';
const Gator = require('gator');

export module AjaxContainer
{
    export function init(): void
    {
        Gator(document.querySelector('main')).on(
            'click',
            '#clear-ajax',
            (event: Event): void => {
                event.preventDefault();
                event.stopPropagation();

                clearAjaxContainer();
            }
        );
    }

    export function clearAjaxContainer(): void
    {
        getContainer().innerHTML = '';
        removeClass(getContainer(), 'active');
    }

    export function displayAjaxContainerWithContent(content: string): void
    {
        getContainer().innerHTML = content;
        trigger(getContainer(), 'ckeditor:initialize');
        addClass(getContainer(), 'active');
    }

    export function getContainer(): HTMLElement
    {
        return document.getElementById('ajax-container');
    }
}
