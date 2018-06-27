import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials';
import AutoformatPlugin from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold';
import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic';
import BlockquotePlugin from '@ckeditor/ckeditor5-block-quote/src/blockquote';
import HeadingPlugin from '@ckeditor/ckeditor5-heading/src/heading';
import LinkPlugin from '@ckeditor/ckeditor5-link/src/link';
import ListPlugin from '@ckeditor/ckeditor5-list/src/list';
import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph';

ready(function () {
    initializeCKEditor(document.querySelector('.ckeditor'));

    const ajaxContainer = document.getElementById('ajax-container');
    ajaxContainer.addEventListener('ckeditor:initialize', function (e) {
        initializeCKEditor(ajaxContainer.querySelector('.ckeditor'));
    }, false);
});

function initializeCKEditor(elements)
{
    if (typeof elements === 'undefined'
        || elements === null
        || 0 === elements.length
    ) {
        return;
    }

    ClassicEditor.create(elements, {
        plugins: [
            EssentialsPlugin,
            AutoformatPlugin,
            BoldPlugin,
            ItalicPlugin,
            BlockquotePlugin,
            HeadingPlugin,
            LinkPlugin,
            ListPlugin,
            ParagraphPlugin
        ],
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

    editor.model.document.on('change', function() {
        if (typeof editor.element === 'undefined') {
            return;
        }

        const element = editor.element;
        const form = findAncestor(element, 'form');
        const id = element.getAttribute('id');
        if (savesScheduled[id] || editor.data.get() === element.value) {
            return;
        }

        const url = form.getAttribute('action') ? form.getAttribute('action') : window.location.href;
        window.setTimeout(function () {
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
            request.onerror = function () {
                savesScheduled[id] = false;
            };
            request.send(new FormData(form));
        }, 1200);
    });
}

function displayAlerts()
{
    const alerts = document.getElementById('alerts');
    const request = new XMLHttpRequest();
    request.open('GET', alerts.getAttribute('data-alert-url'), true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.responseType = 'json';
    request.onload = function () {
        if (200 === request.status) {
            const response = request.response;
            if (typeof response.alerts !== 'undefined' && '' !== response.alerts) {
                alerts.innerHTML = response.alerts;
                Array.prototype.filter.call(alerts.querySelectorAll('.alert'), function (alert) {
                    window.setTimeout(function() {
                        fadeOut(alert, 1);
                    }, 5000);
                });
            }
        }
    };

    request.send();
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
