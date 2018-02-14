import * as $ from 'jquery';
import * as autofocus from './autofocus';
import * as sidemenu from './sidemenu';
import * as forms from './forms';
import * as backdrop from './backdrop';
import * as display from './display';
import './lists';

(function () {
    $(function () {
        display.closeModal();
        autofocus.onStatic();
        sidemenu.toggle();

//        $(document).on('ajaxStart', backdrop.showBackdrop());
//        $(document).on('ajaxComplete', backdrop.hideBackdrop());
//        $(document).on('ajaxError', backdrop.hideBackdrop());

        $('main').on('click', '.js-load-form', function (event : JQuery.Event) {
            event.preventDefault();
            event.stopPropagation();

            let $this : JQuery<HTMLElement> = $(event.currentTarget);
            let $listTable : JQuery<HTMLElement> = $this.parents('.js-list').first();
            let url : string = $this.hasClass('js-edit-form')
                ? $this.data('form-url')
                : $listTable.data('form-url')
            ;

            forms.getForm(url, $listTable);
            $('html, body').animate({
                scrollTop: $("#clear-ajax").offset().top
            }, 2000);
        });
    });
})();
