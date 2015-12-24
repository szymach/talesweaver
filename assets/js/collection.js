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
                var newRowHtml = template.replace(/__name__label__/g, nextIndex).replace(/__name__/g, nextIndex);
                var newRow = $(newRowHtml)
                    .insertBefore(addBtn);

                $el.data('current_index', nextIndex + 1);

                newRow.find('> .col-lg-2').remove();
                newRow.find('> .col-lg-10').removeClass('col-lg-10').addClass('col-lg-11').after(removeCol.clone(true));

                event.preventDefault();
            });

        var removeCol = $('<div />').addClass('col-lg-1');
        var removeBtn = $('<a href="#">X</a>')
            .addClass('remove btn btn-danger')
            .appendTo(removeCol)
            .on('click', function(event) {
                $(this).closest('.form-group').remove();
                event.preventDefault();
            });

        $el.find('> .form-group > .col-lg-2').remove();
        $el.find('> .form-group > .col-lg-10').removeClass('col-lg-10').addClass('col-lg-11').after(removeCol);
    });
});
