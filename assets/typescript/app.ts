import * as $ from 'jquery';
import './forms';
import './backdrop';
import * as display from './display';
import * as alerts from './alerts';
import './lists';

(function () {
    $(function () {
        alerts.closeAlert();
        alerts.setAlertFadeOuts();
    });
})();
