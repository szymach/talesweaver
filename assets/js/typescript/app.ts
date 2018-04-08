import * as $ from 'jquery';
import * as autofocus from './autofocus';
import './forms';
import './backdrop';
import * as display from './display';
import * as alerts from './alerts';
import ckeditor = require('../ckeditor');
import './lists';

(function () {
    $(function () {
        display.closeModal();
        autofocus.onStatic();
        alerts.setAlertFadeOuts();
        ckeditor.initializeCKEditor(document.querySelector('.ckeditor'));
    });
})();
