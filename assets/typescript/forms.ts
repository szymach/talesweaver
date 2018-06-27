import {displayAjaxContainerWithContent, getAjaxContainer, clearAjaxContainer} from './ajax-container';
import {displayAlerts} from './alerts';
import {closeSublists, closeMobileSublists} from './lists';
import {closeAllModals} from './display';

$('main, .modal').on('click', '.js-load-form', function (event : JQuery.Event): void {
    event.preventDefault();
    event.stopPropagation();

    closeAllModals();
    getForm($(event.currentTarget).data('form-url'));
    $('html, body').animate({
        scrollTop: $("#clear-ajax").offset().top
    }, 2000);
});

export function getForm(url : string): void
{
    closeSublists();
    closeMobileSublists();
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json",
        success: function(response : any) {
            displayAjaxContainerWithContent(response.form);
            bindAjaxForm();
            triggerAutofocus();
        }
    });
}

function bindAjaxForm(): void
{
    const $container = getAjaxContainer();
    $container.off('submit');
    $container.on('submit', '.js-form', function (event : JQuery.Event) {
        event.preventDefault();
        event.stopPropagation();

        submitForm($(event.currentTarget));
        const $input : JQuery<HTMLElement> = $container.find('form input').first();
        if (0 !== $input.length) {
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
            clearAjaxContainer();
            displayAlerts();
        },
        error: function(xhr : any) {
            clearAjaxContainer();
            const response : any = JSON.parse(xhr.responseText);
            if (typeof response.form !== 'undefined') {
                displayAjaxContainerWithContent(response.form);
            }
            displayAlerts();
        }
    });
}

function triggerAutofocus(): void
{
    const input : JQuery<HTMLElement> = getAjaxContainer().find('[autofocus="autofocus"]').first();
    if (typeof input === 'undefined') {
        return;
    }

    input.trigger('focus');
}
