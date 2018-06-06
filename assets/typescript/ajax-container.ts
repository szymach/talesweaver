import {showBackdrop, hideBackdrop} from './backdrop';

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

getAjaxClearButton().on('click', function() : void {
    showBackdrop();
    clearAjaxContainer();
    hideBackdrop();
});
