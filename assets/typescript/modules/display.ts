import {Lists} from './lists';
import 'bootstrap';

export module Display {
    export function closeAllModals() : void
    {
        $('.modal.in').find('[data-dismiss=modal]').trigger('click');
    }

    export function init() {
        $('main').on('click', '.js-display', function (event : JQuery.Event) : void {
            event.preventDefault();
            event.stopPropagation();

            Lists.closeMobileSublists();
            const $this : JQuery<HTMLElement> = $(this);
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

        $('#modal-display').on('hidden.bs.modal', function (event : JQuery.Event) : void {
            const $this : JQuery<HTMLElement> = $(this)
            const classToRemove : string = $this.data('class-to-remove');
            if (typeof classToRemove !== 'undefined') {
                $this.find('.modal-dialog').removeClass(classToRemove);
                $this.removeAttr('class-to-remove');
            }
        });
    }
}
