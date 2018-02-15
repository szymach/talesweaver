import * as $ from 'jquery';

export function showBackdrop()
{
    setCursor('wait');
    getBackdrop().addClass('active');
}

export function hideBackdrop()
{
    setCursor('default');
    getBackdrop().removeClass('active');
}

function getBackdrop()
{
    return $('#backdrop');
}

function setCursor(value : string)
{
    $('html').css('cursor', value);
}