const bootstrap = require('bootstrap.native/dist/bootstrap-native-v4');
import { addClass, ready, removeClass, offset } from './common';

ready((): void => {
    document.querySelectorAll('[data-toggle="collapse"]').forEach((element: Element) => {
        new bootstrap.Collapse(element);
    });

    document.querySelectorAll('.collapse').forEach((element: Element) => {
        element.addEventListener(
            'shown.bs.collapse',
            (event: Event): void => {
                const target = event.target as HTMLElement;
                const toggle = getCollapseToggle(target.getAttribute('id'));
                removeClass(toggle, 'fa-toggle-off');
                addClass(toggle, 'fa-toggle-on');
            }
        );
        element.addEventListener(
            'hidden.bs.collapse',
            (event: Event): void => {
                const target = event.target as HTMLElement;
                const toggle = getCollapseToggle(target.getAttribute('id'));
                removeClass(toggle, 'fa-toggle-on');
                addClass(toggle, 'fa-toggle-off');
            }
        );
    });
});

function getCollapseToggle(id: string): HTMLElement {
    return document.querySelector('[data-toggle="collapse"][data-target="#' + id + '"] .fa') as HTMLElement;
}
