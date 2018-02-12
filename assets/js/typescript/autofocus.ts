import * as $ from 'jquery';

export function onStatic() {
    let staticInputs = [
        'form [name="_username"]',
        'form[name="reset_password_request"] [name="reset_password_request[username]"]',
        'form[name="register"] [name="register[username]"]',
        'form[name="create"] [name="create[title]"]',
        'form[name="edit"] [name="edit[title]"]',
        'form[name="create"] [name="create[name]"]',
        'form[name="edit"] [name="edit[name]"]'
    ];

    Array.prototype.forEach.call(staticInputs, function (field : string) {
        let $input = $(field);
        if ($input.length) {
            $input.trigger('focus');
        }
    });
}
