(function () {
    $(function () {
        closeAlert();
        setAlertFadeOuts();
    });
})();

export function displayAlerts() : void
{
    let $alerts : JQuery<HTMLElement> = $('#alerts');
    $.ajax({
        method: "GET",
        url: $alerts.data('alert-url'),
        success: function (response : any) {
            if (typeof response.alerts !== 'undefined') {
                $alerts.append(response.alerts);
                setAlertFadeOuts();
            }
        }
    });
}

export function setAlertFadeOuts() : void
{
    $('#alerts .alert').filter(':visible').each(function (index : number, alert : HTMLElement) {
        const $alert : JQuery<HTMLElement> = $(alert);
        window.setTimeout(function() {
            $alert.fadeOut(800, function () {
                $alert.remove();
            });
        }, 3000);
    });
}

export function closeAlert() : void
{
    $('.alert .close').on('click', function (event : JQuery.Event) {
        $(event.currentTarget).parents('.alert').hide();
    });
}
