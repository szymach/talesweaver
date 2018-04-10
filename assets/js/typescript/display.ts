import * as $ from 'jquery';
import 'bootstrap';

export function closeModal()
{
    $('.alert .close').on('click', function (event : JQuery.Event) {
        $(event.currentTarget).parents('.alert').hide();
    });
}

export function closeAllModals()
{
    $('.modal.in').find('[data-dismiss=modal]').trigger('click');
}

$('main').on('click', '.js-display', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    $.ajax({
        method: "GET",
        url: $(event.currentTarget).data('display-url'),
        dataType: "json",
        success: function(response : any) {
            $('#modal-display').find('.modal-content').html(response.display);
            $('#modal-display').modal();
        }
    });
});
