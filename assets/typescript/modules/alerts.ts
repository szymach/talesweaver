import { ajaxGetCall, findAncestor, hide, fadeOut } from '../common';

interface AlertsResponse
{
    alerts?: string | null
}

export module Alerts
{
    export function init(): void
    {
        setAlertFadeOuts();
    }

    export function displayAlerts(): void
    {
        const alerts = document.getElementById('alerts');
        ajaxGetCall(
            alerts.getAttribute('data-alert-url'),
            function (): void {
                const response: AlertsResponse = this.response;
                if (null !== response.alerts) {
                    alerts.insertAdjacentHTML('beforeend', response.alerts);
                    setAlertFadeOuts();
                }
            }
        );
    }

    export function displayErrorAlert(): void
    {
        const alerts = document.getElementById('alerts');
        alerts.insertAdjacentHTML('beforeend', '<p class="alert alert-danger">Wystąpił błąd</p>');
    }

    function setAlertFadeOuts(): void
    {
        document.querySelectorAll('.alert .close').forEach(
            (element: Element): void => {
                element.addEventListener('click', (event: Event): void => {
                    const target = event.target as HTMLElement;
                    hide(findAncestor(target, '.alert'));
                })
            }
        );

        document.querySelectorAll('#alerts .alert').forEach(
            (alert: Element): void => {
                window.setTimeout((): void => {
                    fadeOut(alert, 1);
                }, 3000);
            }
        );
    }
}
