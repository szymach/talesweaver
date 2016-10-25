var $ = require('jquery');
$(document).ready(function() {
    $('.tag-add').on('click', function() {
        surroundSelection(document.createElement("span"));
    });
});

function getCKEditorSelection()
{
    var instanceId = $('.cke').attr('id').replace('cke_', '');
    var editor = CKEDITOR.instances[instanceId];
    var mySelection = editor.getSelection();

    if (CKEDITOR.env.ie) {
        mySelection.unlock(true);
    }

    return mySelection;
}

function surroundSelection(element)
{
    var selection = getCKEditorSelection();
    var nativeSelection = selection.getNative();
    var range = nativeSelection.getRangeAt(0).cloneRange();
    range.surroundContents(element);
    nativeSelection.removeAllRanges();
    nativeSelection.addRange(range);

    if (CKEDITOR.env.ie) {
        selection.lock();
    }
}
