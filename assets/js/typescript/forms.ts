import * as $ from 'jquery';
import * as ajaxContainer from './ajax-container';
import * as alerts from './alerts';
import * as lists from './lists';
import * as display from './display';

$('main, .modal').on('click', '.js-load-form', function (event : JQuery.Event): void {
    event.preventDefault();
    event.stopPropagation();

    display.closeAllModals();
    getForm($(event.currentTarget).data('form-url'));
    $('html, body').animate({
        scrollTop: $("#clear-ajax").offset().top
    }, 2000);
});

export function getForm(url : string): void
{
    lists.closeSublists();
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json",
        success: function(response : any) {
            ajaxContainer.displayAjaxContainerWithContent(response.form);
            bindAjaxForm();
            triggerAutofocus();
        }
    });
}

function bindAjaxForm(): void
{
    let $container = ajaxContainer.getAjaxContainer();
    $container.off('submit');
    $container.on('submit', '.js-form', function (event : JQuery.Event) {
        event.preventDefault();
        event.stopPropagation();

        submitForm($(event.currentTarget));
        var $input : JQuery<HTMLElement> = $container.find('form input').first();
        if ($input.length) {
            $input.trigger('focus');
        }
        return false;
    });
}

function submitForm($form : any): void
{
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        processData: false,
        contentType: false,
        data: new FormData($form[0]),
        success: function() {
            ajaxContainer.clearAjaxContainer();
            alerts.displayAlerts();
        },
        error: function(xhr : any) {
            ajaxContainer.clearAjaxContainer();
            let response : any = JSON.parse(xhr.responseText);
            if (typeof response.form !== 'undefined') {
                ajaxContainer.displayAjaxContainerWithContent(response.form);
            }
            alerts.displayAlerts();
        }
    });
}

function triggerAutofocus(): void
{
    const input : JQuery<HTMLElement> = ajaxContainer.getAjaxContainer().find('[autofocus="autofocus"]').first();
    if (typeof input === 'undefined') {
        return;
    }

    input.trigger('focus');
}