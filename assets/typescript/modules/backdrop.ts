import { addClass, removeClass, setCursor } from '../common';

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
        return document.getElementById('form-backdrop');
    }
}
