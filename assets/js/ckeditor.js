import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

ready(() => {
    initializeCKEditor(document.querySelector('.ckeditor'));

    const ajaxContainer = document.getElementById('ajax-container');
    ajaxContainer.addEventListener('ckeditor:initialize', () => {
        initializeCKEditor(ajaxContainer.querySelector('.ckeditor'));
    }, false);
});

function initializeCKEditor(elements)
{
    if (typeof elements === 'undefined'
        || null === elements
        || 0 === elements.length
    ) {
        return;
    }

    ClassicEditor.create(elements, {
        language: document.querySelector('html').getAttribute('lang'),
        toolbar: [
            'heading',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            'blockQuote',
            'undo',
            'redo'
        ]
    }).then(editor => {
        bindAutosave(editor);
    }).catch(error => {
        console.error(error);
    });
}

const savesScheduled = [];
function bindAutosave(editor)
{
    if (typeof editor === 'undefined') {
        return;
    }

    editor.model.document.on('change', () => {
        if (typeof editor.element === 'undefined') {
            return;
        }

        const element = editor.element;
        const form = findAncestor(element, 'form');
        if (false === hasClass(form, 'autosave')) {
            return;
        }

        const id = element.getAttribute('id');
        if (savesScheduled[id] || editor.data.get() === element.value) {
            return;
        }

        const url = form.getAttribute('action') ? form.getAttribute('action') : window.location.href;
        window.setTimeout(() => {
            element.value = editor.data.get();
            savesScheduled[id] = true;
            const request = new XMLHttpRequest();
            request.open('POST', url, true);
            request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            request.responseType = 'json';
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    displayAlerts();
                } else {
                    let response = request.response;
                    if (typeof response.form !== 'undefined') {
                        form.outerHTML = response.form;
                    }
                }
                savesScheduled[id] = false;
            };
            request.onerror = () => {
                savesScheduled[id] = false;
            };
            request.send(new FormData(form));
        }, 30000000);
    });
}

function displayAlerts()
{
    const alerts = document.getElementById('alerts');
    const request = new XMLHttpRequest();
    request.open('GET', alerts.getAttribute('data-alert-url'), true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.responseType = 'json';
    request.onload = () => {
        if (200 === request.status) {
            const response = request.response;
            if (typeof response.alerts !== 'undefined' && '' !== response.alerts) {
                alerts.innerHTML = response.alerts;
                Array.prototype.filter.call(alerts.querySelectorAll('.alert'), (alert) => {
                    window.setTimeout(() => {
                        fadeOut(alert, 1);
                    }, 30000);
                });
            }
        }
    };

    request.send();
}

function hasClass(element, className)
{
    let check;
    if (element.classList) {
        check = element.classList.contains(className);
    } else {
        check = new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
    }

    return check;
}

function findAncestor(el, sel)
{
    while ((el = el.parentElement) && !((el.matches || el.matchesSelector).call(el, sel)));
    return el;
}

function ready(fn)
{
    if (document.attachEvent ? "complete" === document.readyState : "loading" !== document.readyState) {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

function fadeOut(fadeTarget, duration)
{
    let transitionStates = '-webkit-transition: opacity ' + duration + 's ease-in-out;' +
        '-moz-transition: opacity ' + duration + 's ease-in-out;' +
        '-o-transition: opacity ' + duration + 's ease-in-out;' +
        'transition: opacity ' + duration + 's ease-in-out;' +
        'opacity: 0;'
    ;
    fadeTarget.setAttribute('style', transitionStates);
    fadeTarget.parentElement.removeChild(fadeTarget);
}
