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
import * as $ from 'jquery';

export function initializeCKEditor(elements) {
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
            'headings',
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

var savesScheduled = [];
function bindAutosave(editor)
{
    editor.document.on('changesDone', function() {
        var $element = $(editor.element);
        var $form = $element.parents('form');
        var id = $form.attr('id');
        if (savesScheduled[id] || editor.data.get() === $element.val()) {
            return;
        }

        savesScheduled[id] = true;
        window.setTimeout(function () {
            $element.val(editor.data.get());
            $.ajax({
                method: "POST",
                url: $form.attr('action'),
                processData: false,
                contentType: false,
                data: new FormData($form[0]),
                success: function() {
                    displayAlerts();
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    if (typeof response.form !== 'undefined') {
                        $form.replaceWith($(response.form));
                    }
                },
                complete: function () {
                    savesScheduled[id] = false;
                }
            });
        }, 10000);
    });
}

function displayAlerts()
{
    $.ajax({
        method: "GET",
        url: $('#alerts').data('alert-url'),
        success : function (response) {
            if (typeof response.alerts !== 'undefined') {
                $('#alerts').append(response.alerts);
                $('#alerts .alert').filter(':visible').each (function (index, alert) {
                    var $alert = $(alert);
                    window.setTimeout(function() {
                        $alert.fadeOut(800, function () { $alert.remove(); });
                    }, 5000);
                });
            }
        }
    });
}
