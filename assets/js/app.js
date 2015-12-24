$(document).ready(function() {
    $('.datagrid-delete').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
            method: "DELETE",
            url: $(this).attr('href')
        })
        .success(function() {
            $(this).parents('tr').remove();
        });
    });
});
