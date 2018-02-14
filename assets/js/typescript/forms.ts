import * as $ from 'jquery';
import * as autofocus from './autofocus';
import * as ajaxContainer from './ajax-container';
import * as alerts from './alerts';
import * as lists from './lists';

export function getForm(url : string, $listTable : JQuery<HTMLElement>)
{
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json",
        success: function(response : any) {
            ajaxContainer.displayAjaxContainerWithContent(response.form);
            bindAjaxForm($listTable);
            autofocus.onStatic();
        }
    });
}

function bindAjaxForm($listTable : JQuery<HTMLElement>)
{
    let $container = ajaxContainer.getAjaxContainer();
    $container.off('submit');
    $container.on('submit', '.js-form', function (event : JQuery.Event) {
        event.preventDefault();
        event.stopPropagation();

        submitForm($(event.currentTarget), $listTable);
        var $input : JQuery<HTMLElement> = $container.find('form input').first();
        if ($input.length) {
            $input.trigger('focus');
        }
        return false;
    });
}

function submitForm($form : JQuery<HTMLElement>, $listTable : JQuery<HTMLElement>)
{
    let form : HTMLFormElement = new HTMLFormElement();
    form.outerHtml = $form.html();
    $.ajax({
        method: "POST",
        url: $form.attr('action'),
        processData: false,
        contentType: false,
        data: new FormData(form),
        success: function() {
            ajaxContainer.clearAjaxContainer();
            lists.refreshList($listTable);
            alerts.displayAlerts();
        },
        error: function(xhr) {
            ajaxContainer.clearAjaxContainer();
            let response : any = JSON.parse(xhr.responseText);
            if (typeof response.form !== 'undefined') {
                ajaxContainer.displayAjaxContainerWithContent(response.form);
                bindAjaxForm($listTable);
            }
            alerts.displayAlerts();
        }
    });
}
