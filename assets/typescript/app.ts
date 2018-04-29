import './forms';
import './backdrop';
import './display';
import './alerts';
import './lists';

(function () {
    $(function () {
        // workaround dropdowns not working with data-toggle="dropdown"
        $('.dropdown-toggle').dropdown();
    });
})();
