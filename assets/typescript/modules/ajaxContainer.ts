import { addClass, hide, removeClass, show, trigger } from '../common';

export module AjaxContainer
{
    export function init(): void
    {
        const clearAjaxButton = getClearAjaxButton();
        if (null === clearAjaxButton) {
            return;
        }

        clearAjaxButton.addEventListener('click', () => {
            clearAjaxContainer();
        });
    }

    export function clearAjaxContainer(): void
    {
        hideClearButton();
        getContainer().innerHTML = '';
        removeClass(getContainer(), 'active');
    }

    export function displayAjaxContainerWithContent(content: string): void
    {
        getContainer().innerHTML = content;
        showClearButton();
        trigger(getContainer(), 'ckeditor:initialize');
        addClass(getContainer(), 'active');
    }

    export function showClearButton(): void
    {
        show(getClearAjaxButton());
    }

    export function hideClearButton(): void
    {
        hide(getClearAjaxButton());
    }

    export function getContainer(): HTMLElement
    {
        return document.getElementById('ajax-container');
    }

    export function getClearAjaxButton(): HTMLElement
    {
        return document.getElementById('clear-ajax');
    }
}
