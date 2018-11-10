import { ajaxGetCall, findAncestor, hide, fadeOut } from '../common';
export module Alerts
{
    export function init() : void
    {
        document.querySelectorAll('.alert .close').forEach((element : Element) => {
            element.addEventListener('click', (event : Event) : void => {
                const target = event.target as HTMLElement;
                hide(findAncestor(target, '.alert'));
            })
        });

        setAlertFadeOuts();
    }

    export function displayAlerts() : void
    {
        const alerts = document.getElementById('alerts');
        ajaxGetCall(
            alerts.getAttribute('data-alert-url'),
            function () {
                const response : { alerts: string } = this.response;
                if (null !== response.alerts) {
                    alerts.insertAdjacentHTML('beforeend', response.alerts);
                    setAlertFadeOuts();
                }
            }
        );
    }

    function setAlertFadeOuts() : void {
        document.querySelectorAll('#alerts .alert').forEach((alert : Element) : void => {
            window.setTimeout(() => {
                fadeOut(alert, 1);
            }, 3000);
        });
    }
}
