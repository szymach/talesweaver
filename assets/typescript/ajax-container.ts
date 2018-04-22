import * as $ from 'jquery';
import * as backdrop from './backdrop';

export function clearAjaxContainer()
{
    const $container : JQuery<HTMLElement> = getAjaxContainer();
    getAjaxClearButton().hide();
    $container.html('');
    $container.removeClass('active');
}

export function displayAjaxContainerWithContent(content : string)
{
    const $container : JQuery<HTMLElement> = getAjaxContainer();
    $container.html(content);
    getAjaxClearButton().show();
    $container.trigger('ckeditor');
    $container.addClass('active');
}

export function getAjaxContainer()
{
    return $('#ajax-container');
}

export function getAjaxClearButton()
{
    return $('#clear-ajax');
}

getAjaxClearButton().on('click', function() {
    backdrop.showBackdrop();
    clearAjaxContainer();
    backdrop.hideBackdrop();
});
