import {AjaxContainer} from './modules/ajaxContainer';
import {Alerts} from './modules/alerts';
import {Backdrop} from './modules/backdrop';
import {Display} from './modules/display';
import {Forms} from './modules/forms';
import {Lists} from './modules/lists';

(function () {
    $(function () {
        AjaxContainer.init();
        Alerts.init();
        Backdrop.init();
        Display.init();
        Forms.init();
        Lists.init();
        // workaround dropdowns not working with data-toggle="dropdown"
        $('.dropdown-toggle').dropdown();
    });
})();
