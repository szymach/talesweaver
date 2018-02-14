import * as $ from 'jquery';

export function toggle() {
    $('main').on('click', '.side-menu .side-menu-toggle', function (event : JQuery.Event) {
        let $this : JQuery<HTMLElement> = $(event.currentTarget);
        if ($('.side-menu').hasClass('expanded')) {
            $this.find('.fa').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-left');
            $('.side-menu').removeClass('expanded');
        } else {
            $this.find('.fa').removeClass('fa-arrow-circle-left').addClass('fa-arrow-circle-right');
            $('.side-menu').addClass('expanded');
        }
    });
}
