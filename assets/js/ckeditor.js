import ClassicEditor from 'ckeditor';

ready(() => {
    initializeCKEditor(document.querySelector('.ckeditor'));

    const formModal = document.getElementById('modal-form');
    formModal.addEventListener('ckeditor:initialize', () => {
        initializeCKEditor(formModal.querySelector('.ckeditor'));
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
            'alignment',
            'link',
            'bulletedList',
            'numberedList',
            'blockQuote',
            'undo',
            'redo'
        ],
        alignment: {
            options: [ 'left', 'justify', 'right' ]
        },
        autosave: {
            save(editor) {
                if (typeof editor === 'undefined' || typeof editor.sourceElement === 'undefined') {
                    return;
                }

                const element = editor.sourceElement;
                if (element.value === editor.getData()) {
                    return;
                }
                const form = findAncestor(element, 'form');
                if (false === hasClass(form, 'autosave')) {
                    return;
                }

                return new Promise((resolve, reject) => {
                    const url = form.getAttribute('action') ? form.getAttribute('action') : window.location.href;
                    element.value = editor.getData();

                    const request = new XMLHttpRequest();
                    request.open('POST', url, true);
                    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    request.responseType = 'json';
                    request.onload = function () {
                        if (request.status < 200 && request.status >= 400) {
                            let response = request.response;
                            if (typeof response.form !== 'undefined') {
                                form.outerHTML = response.form;
                            }
                        }
                        resolve();
                    };
                    request.onerror = () => {
                        reject('Request failed');
                    };
                    request.send(new FormData(form));
                });
            }
        },
    }).catch(error => {
        console.error(error);
    });
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
