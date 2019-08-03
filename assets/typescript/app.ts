import { Alerts } from './modules/alerts';
import { Display } from './modules/display';
import { Forms } from './modules/forms';
import { Lists } from './modules/lists';
import { ready } from './common';
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
});
