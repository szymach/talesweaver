import { Alerts } from './modules/alerts';
import { Display } from './modules/display';
import { Forms } from './modules/forms';
import { Lists } from './modules/lists';
import { addClass, ready, removeClass } from './common';
const bootstrap = require('bootstrap.native/dist/bootstrap-native-v4');

ready((): void => {
    Alerts.init();
    Display.init();
    Forms.init();
    Lists.init();

    document.querySelectorAll('[data-toggle="collapse"]').forEach((element: Element) => {
        new bootstrap.Collapse(element);
    });
    document.querySelectorAll('.dropdown-toggle').forEach((element: Element) => {
        new bootstrap.Dropdown(element);
    });
    toggleSidemenuIcon();
});

function toggleSidemenuIcon(): void {
    const sideMenu = document.getElementById('side-menu');
    if (null === sideMenu || typeof sideMenu === 'undefined') {
        return;
    }

    const toggle = document.querySelector('[data-target="#side-menu"][data-toggle="collapse"] button .fa') as HTMLElement;
    sideMenu.addEventListener(
        'hide.bs.collapse',
        (): void => {
            removeClass(toggle, 'fa-chevron-up');
            addClass(toggle, 'fa-chevron-down');
        }
    );
    sideMenu.addEventListener(
        'show.bs.collapse',
        (): void => {
            removeClass(toggle, 'fa-chevron-down');
            addClass(toggle, 'fa-chevron-up');
        }
    );
}
