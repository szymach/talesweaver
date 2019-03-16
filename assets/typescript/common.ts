import { Backdrop } from './modules/backdrop';

export function hasClass(element : HTMLElement, className: string): boolean
{
    let check;
    if (element.classList) {
        check = element.classList.contains(className);
    } else {
        check = new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
    }

    return check;
}

export function addClass(element: HTMLElement, className : string) : void
{
    if (null === element) {
        return;
    }

    if (element.classList) {
        element.classList.add(className);
    } else {
        element.className += ' ' + className;
    }
}

export function removeClass(element: HTMLElement, className : string) : void
{
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

export function findAncestor(element : HTMLElement, selector : string) : HTMLElement
{
    while (
        (element = element.parentElement)
        && !((element.matches || element.matches).call(element, selector))
    );
    return element;
}

export function ajaxGetCall(
    url : string,
    onLoad : any,
    onError? : any
) : void {
    const request = new XMLHttpRequest();
    request.onloadstart = Backdrop.showBackdrop;
    request.onloadend = Backdrop.hideBackdrop;
    request.onerror = Backdrop.hideBackdrop;
    request.open('GET', url, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.responseType = 'json';
    request.onload = onLoad;
    if (null !== onError) {
        request.onerror = onError;
    }

    request.send();
}

export function ajaxPostCall(
    url : string,
    data : FormData,
    onLoad : any,
    onError? : any
) : void {
    const request = new XMLHttpRequest();
    request.onloadstart = Backdrop.showBackdrop;
    request.onloadend = Backdrop.hideBackdrop;
    request.onerror = Backdrop.hideBackdrop;
    request.open('POST', url, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.responseType = 'json';
    request.onload = onLoad;
    if (null !== onError) {
        request.onerror = onError;
    }

    request.send(data);
}

export function trigger(element : HTMLElement, eventName : string): void
{
    if (null === element) {
        return;
    }

    const event = document.createEvent('HTMLEvents');
    event.initEvent(eventName, true, false);
    element.dispatchEvent(event);
}

export function show(element : HTMLElement): void
{
    if (null === element) {
        return;
    }

    element.style.display = '';
}

export function hide(element : HTMLElement): void
{
    if (null === element) {
        return;
    }

    element.style.display = 'none';
}

export function offset(element : HTMLElement) : {top : number, left : number}
{
    const rectangle = element.getBoundingClientRect();
    return {
        top: rectangle.top + document.body.scrollTop,
        left: rectangle.left + document.body.scrollLeft
    }
}


export function scrollTo(to : number, duration : number)
{
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

export function ready(callback : () => void): void
{
    if (document.readyState !== "loading") {
        callback();
    } else {
        document.addEventListener("DOMContentLoaded", callback);
    }
}

export function fadeOut(fadeTarget : Element, duration : number): void
{
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

// export function eventTarget(event : Event, selector : string): HTMLElement
// {
//     const target = event.currentTarget;
// }
