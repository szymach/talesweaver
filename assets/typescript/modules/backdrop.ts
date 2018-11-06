export module Backdrop {
    export function init() {
        $.bind('ajaxStart', showBackdrop());
        $.bind('ajaxComplete', hideBackdrop());
        $.bind('ajaxError', hideBackdrop());
    }

    export function showBackdrop() : void
    {
        setCursor('wait');
        getBackdrop().addClass('active');
    }

    export function hideBackdrop() : void
    {
        setCursor('default');
        getBackdrop().removeClass('active');
    }

    function getBackdrop() : JQuery<HTMLElement>
    {
        return $('#backdrop');
    }

    function setCursor(value : string) : void
    {
        $('html').css('cursor', value);
    }
}
