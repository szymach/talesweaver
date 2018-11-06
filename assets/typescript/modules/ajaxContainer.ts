import {Backdrop} from './backdrop';

export module AjaxContainer {
    export function init() {
        getAjaxClearButton().on('click', function() : void {
            Backdrop.showBackdrop();
            clearAjaxContainer();
            Backdrop.hideBackdrop();
        });
    }

    export function clearAjaxContainer() : void
    {
        const $container : JQuery<HTMLElement> = getAjaxContainer();
        getAjaxClearButton().hide();
        $container.html('');
        $container.removeClass('active');
    }

    export function displayAjaxContainerWithContent(content : string) : void
    {
        const $container : JQuery<HTMLElement> = getAjaxContainer();
        $container.html(content);
        getAjaxClearButton().show();
        $container.trigger('ckeditor');
        $container.addClass('active');
    }

    export function getAjaxContainer() : JQuery<HTMLElement>
    {
        return $('#ajax-container');
    }

    export function getAjaxClearButton() : JQuery<HTMLElement>
    {
        return $('#clear-ajax');
    }
}
