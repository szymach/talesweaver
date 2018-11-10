import { AjaxContainer } from './modules/ajaxContainer';
import { Alerts } from './modules/alerts';
const bootstrap = require('bootstrap.native');
import { Display } from './modules/display';
import { Forms } from './modules/forms';
import { Lists } from './modules/lists';
import { ready } from './common';

ready((): void => {
    AjaxContainer.init();
    Alerts.init();
    Display.init();
    Forms.init();
    Lists.init();
    // workaround dropdowns not working with data-toggle="dropdown"
    document.querySelectorAll('.dropdown-toggle').forEach((element : Element) => {
        new bootstrap.Dropdown(element);
    });
});
