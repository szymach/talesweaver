import { addClass, removeClass } from '../common';

export module Backdrop
{
    export function showBackdrop(): void
    {
        if (null === getBackdrop()) {
            return;
        }

        setCursor('wait');
        addClass(getBackdrop(), 'active');
    }

    export function hideBackdrop(): void
    {
        if (null === getBackdrop()) {
            return;
        }

        setCursor('default');
        removeClass(getBackdrop(), 'active');
    }

    export function getBackdrop(): HTMLElement
    {
        return document.getElementById('backdrop');
    }

    function setCursor(value: string): void
    {
        document.querySelector('html').style.cursor = value;
    }
}
