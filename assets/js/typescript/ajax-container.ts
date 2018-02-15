import * as $ from 'jquery';
import * as backdrop from './backdrop';

export function getAjaxContainer()
{
    return $('#ajax-container');
}

export function clearAjaxContainer()
{
    let $container : JQuery<HTMLElement> = getAjaxContainer();
    getAjaxClearButton().hide();
    $container.html('');
    $container.removeClass('active');
}

export function displayAjaxContainerWithContent(content : string)
{
    let $container : JQuery<HTMLElement> = getAjaxContainer();
    $container.html(content);
    getAjaxClearButton().show();
    $container.addClass('active');
}

export function getAjaxClearButton()
{
    return $('#clear-ajax');
}

getAjaxClearButton().on('click', function() {
    backdrop.showBackdrop();
    $('html, body').animate({ scrollTop: $("main").offset().top }, 500, function () {
        clearAjaxContainer();
        backdrop.hideBackdrop();
    });
});