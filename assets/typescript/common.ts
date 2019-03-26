import axios, { AxiosResponse } from 'axios';
import { Alerts } from './modules/alerts';
import { Backdrop } from './modules/backdrop';

export function hasClass(element: HTMLElement, className: string): boolean {
    let check;
    if (element.classList) {
        check = element.classList.contains(className);
    } else {
        check = new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
    }

    return check;
}

export function addClass(element: HTMLElement, className: string): void {
    if (null === element) {
        return;
    }

    if (element.classList) {
        element.classList.add(className);
    } else {
        element.className += ' ' + className;
    }
}

export function removeClass(element: HTMLElement, className: string): void {
    if (null === element) {
        return;
    }

    if (element.classList) {
        element.classList.remove(className);
    } else {
        element.className = element.className.replace(
            new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'),
            ' '
        );
    }
}

export function findAncestor(element: HTMLElement, selector: string): HTMLElement {
    while (
        (element = element.parentElement)
        && !((element.matches || element.matches).call(element, selector))
    );
    return element;
}

export function ajaxGetCall(
    url: string,
    successCallback: any,
    errorCallback?: any
): void {
    Backdrop.showBackdrop();
    axios.get(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(
        (response): void => {
            handleSuccessResponse(response, successCallback);
        }
    ).catch(
        (): void => {
            if (true === isDefinedErrorCallback(errorCallback)) {
                errorCallback();
            } else {
                Alerts.displayErrorAlert();
            }
            Backdrop.hideBackdrop();
        }
    );
}

export function ajaxPostCall(
    url: string,
    data: FormData,
    successCallback: any,
    errorCallback?: any
): void {
    Backdrop.showBackdrop();
    axios.post(url, data, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        validateStatus: function (status): boolean {
            return (200 <= status && 300 > status) || 400 === status;
        }
    }).then(
        (response): void => {
            handleSuccessResponse(response, successCallback);
        }
    ).catch(
        (response): void => {
            if (undefined !== response.data && true === isDefinedErrorCallback(errorCallback)) {
                errorCallback(response.data);
            } else {
                Alerts.displayErrorAlert();
            }
            Backdrop.hideBackdrop();
        }
    );
}

export function trigger(element: HTMLElement, eventName: string): void {
    if (null === element) {
        return;
    }

    const event = document.createEvent('HTMLEvents');
    event.initEvent(eventName, true, false);
    element.dispatchEvent(event);
}

export function show(element: HTMLElement): void {
    if (null === element) {
        return;
    }

    element.style.display = '';
}

export function hide(element: HTMLElement): void {
    if (null === element) {
        return;
    }

    element.style.display = 'none';
}

export function offset(element: HTMLElement): { top: number, left: number } {
    const rectangle = element.getBoundingClientRect();
    return {
        top: rectangle.top + document.body.scrollTop,
        left: rectangle.left + document.body.scrollLeft
    }
}

export function scrollTo(to: number, duration: number) {
    const element = document.scrollingElement || document.documentElement,
        start = element.scrollTop,
        change = to - start,
        startDate = +new Date(),
        easeInOutQuad = (
            currentTime: number,
            startValue: number,
            valueChange: number,
            duration: number
        ) => {
            currentTime /= duration / 2;
            if (currentTime < 1) {
                return valueChange / 2 * currentTime * currentTime + startValue;
            }

            currentTime--;
            return -valueChange / 2 * (currentTime * (currentTime - 2) - 1) + startValue;
        },
        animateScroll = () => {
            const currentDate = +new Date();
            const currentTime = currentDate - startDate;
            element.scrollTop = easeInOutQuad(currentTime, start, change, duration);
            if (currentTime < duration) {
                requestAnimationFrame(animateScroll);
            } else {
                element.scrollTop = to;
            }
        }
        ;
    animateScroll();
};

export function ready(callback: () => void): void {
    if (document.readyState !== "loading") {
        callback();
    } else {
        document.addEventListener("DOMContentLoaded", callback);
    }
}

export function fadeOut(fadeTarget: Element, duration: number): void {
    const transitionStates = '-webkit-transition-: opacity ' + duration + 's ease-in-out;' +
        ' -moz-transition-: opacity ' + duration + 's ease-in-out;' +
        ' -o-transition-: opacity ' + duration + 's ease-in-out;' +
        ' transition: opacity ' + duration + 's ease-in-out;' +
        ' opacity: 0;'
        ;
    fadeTarget.setAttribute('style', transitionStates);
    window.setTimeout(() => {
        fadeTarget.parentElement.removeChild(fadeTarget)
    }, duration * 1000);
}

function handleSuccessResponse(response: AxiosResponse, successCallback: any): void {
    if (response.request.responseURL.endsWith('/login')) {
        Alerts.displaySessionTimedoutAlert();
    } else {
        successCallback(response.data);
    }
    Backdrop.hideBackdrop();
}

function isDefinedErrorCallback(errorCallback: any): boolean {
    return null !== errorCallback && undefined !== errorCallback;
}
