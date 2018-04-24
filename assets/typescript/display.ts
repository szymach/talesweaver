import * as $ from 'jquery';
import {closeMobileSublists} from './lists';
import 'bootstrap';

export function closeAllModals()
{
    $('.modal.in').find('[data-dismiss=modal]').trigger('click');
}

$('main').on('click', '.js-display', function (event : JQuery.Event) {
    event.preventDefault();
    event.stopPropagation();

    closeMobileSublists();
    const $this : JQuery<HTMLElement> = $(event.currentTarget);
    $.ajax({
        method: "GET",
        url: $this.data('display-url'),
        dataType: "json",
        success: function(response : any) {
            const $modal = $('#modal-display');
            $modal.find('.modal-content').html(response.display);
            const modalClass : string = $this.data('modal-class');
            if (typeof modalClass !== 'undefined') {
                $modal.find('.modal-dialog').addClass(modalClass);
                $modal.data('class-to-remove', modalClass);
            }
            $modal.modal();
        }
    });
});

$('#modal-display').on('hidden.bs.modal', function (event : JQuery.Event) {
    const $this : JQuery<HTMLElement> = $(event.currentTarget)
    const classToRemove : string = $this.data('class-to-remove');
    if (typeof classToRemove !== 'undefined') {
        $this.find('.modal-dialog').removeClass(classToRemove);
        $this.removeAttr('class-to-remove');
    }
})