import { ajaxGetCall, findAncestor, hide, fadeOut } from '../common';

interface AlertsResponse {
    alerts?: string | null
}

export module Alerts {
    export function init(): void {
        setAlertFadeOuts();
    }

    export function displayAlerts(): void {
        const alerts = getAlerts();
        ajaxGetCall(
            alerts.getAttribute('data-alert-url'),
            function (response: AlertsResponse): void {
                if (null !== response.alerts) {
                    alerts.insertAdjacentHTML('beforeend', response.alerts);
                    setAlertFadeOuts();
                }
            }
        );
    }

    export function displayErrorAlert(): void {
        const alerts = getAlerts();
        alerts.insertAdjacentHTML(
            'beforeend',
            '<p class="alert alert-danger">' + alerts.getAttribute('data-error-content') + '</p>'
        );
        setAlertFadeOuts();
    }

    function setAlertFadeOuts(): void {
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

    function getAlerts(): HTMLElement {
        return document.getElementById('alerts');
    }
}
