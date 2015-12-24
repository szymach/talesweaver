$(document).ready(function() {
    var collectionWrapper = $('[data-prototype]');

    collectionWrapper.each(function(index, element) {

        var $el = $(element);
        $el.data('current_index', $el.children('.form-group').length);
        var template = $el.data('prototype');

        var addBtn = $('<a href="#"><i class="fa fa-plus"></i></a>')
            .appendTo($el)
            .addClass('add btn btn-success')
            .on('click', function(event) {
                var nextIndex = $el.data('current_index');
                var newRowHtml = template.replace(/__name__label__/g, nextIndex)
                    .replace(/__name__/g, nextIndex)
                ;
                var newRow = $(newRowHtml).insertBefore(addBtn);

                $el.data('current_index', nextIndex + 1);

                newRow.find('> label').remove();
                newRow.append(removeCol.clone(true));

                event.preventDefault();
            });

        var removeCol = $('<div />').addClass('col-lg-1');
        $('<a class="fa fa-trash" href="#"></a>')
            .addClass('remove btn btn-danger')
            .appendTo(removeCol);
            

        $el.find('> .form-group > label').remove();
        $el.find('> .form-group').append(removeCol);
    });
    
    $('form').on('click', '.fa-trash', function(event) {
        event.preventDefault();
        $(this).parents('.form-group').first().remove();
    });
});
