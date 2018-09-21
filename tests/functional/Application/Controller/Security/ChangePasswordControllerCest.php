<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

class ChangePasswordControllerCest
{
    private const HEADER = 'Zmiana hasła';

    private const FORM_ROUTE = 'change_password';
    private const FORM = 'form[name="change_password"]';
    private const CURRENT_PASSWORD = 'Aktualne hasło';
    private const NEW_PASSWORD = 'Nowe hasło';
    private const NEW_PASSWORD_REPEAT = 'Powtórz nowe hasło';
    private const SUBMIT = 'Wyślij';
    private const CURRENT_PASSWORD_FIELD = 'change_password[currentPassword]';
    private const NEW_PASSWORD_FIELD = 'change_password[newPassword][first]';
    private const NEW_PASSWORD_REPEAT_FIELD = 'change_password[newPassword][second]';

    private const INCORRECT_CURRENT_PASSWORD = 'bledneHaslo';
    private const NEW_PASSWORD_VALUE = 'newPassword123';
    private const NEW_PASSWORD_INCORRECT_VALUE = '12345';
    private const REPEAT_PASSWORD_INCORRECT_VALUE = 'newPassword321';

    private const USERNAME = 'Email';
    private const PASSWORD = 'Hasło';
    private const LOGIN = 'Zaloguj';

    private const LOGIN_ROUTE = 'login';
    private const DASHBOARD_ROUTE = 'index';

    public function changePasswordFormView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->canSeeIAmOnRouteLocale(self::FORM_ROUTE);
        $I->canSeeInTitle(self::HEADER);
        $I->canSee(self::HEADER, 'h1');
        $I->canSeeElement(self::FORM);
        $I->canSee(self::CURRENT_PASSWORD);
        $I->canSee(self::NEW_PASSWORD);
        $I->canSee(self::SUBMIT);
    }

    public function successfullPasswordChange(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->canSeeIAmOnRouteLocale(self::FORM_ROUTE);
        $I->fillField(self::CURRENT_PASSWORD, FunctionalTester::AUTHOR_PASSWORD);
        $I->fillField(self::NEW_PASSWORD, self::NEW_PASSWORD_VALUE);
        $I->fillField(self::NEW_PASSWORD_REPEAT, self::NEW_PASSWORD_VALUE);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals($I->createUrl(self::LOGIN_ROUTE));
        $I->canSeeAlert('Pomyślnie zmieniono hasło do aplikacji. Wymagane jest ponowne zalogowanie.');
        $I->fillField(self::USERNAME, FunctionalTester::AUTHOR_EMAIL);
        $I->fillField(self::PASSWORD, self::NEW_PASSWORD_VALUE);
        $I->click(self::LOGIN);
        $I->canSeeIAmOnRouteLocale(self::DASHBOARD_ROUTE);
    }

    public function incorrectPasswordSubmit(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->canSeeIAmOnRouteLocale(self::FORM_ROUTE);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals($I->createUrl(self::FORM_ROUTE));
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość powinna być aktualnym hasłem użytkownika.', self::CURRENT_PASSWORD_FIELD);
        $I->seeError('Ta wartość nie powinna być pusta.', self::NEW_PASSWORD_FIELD);

        $I->fillField(self::CURRENT_PASSWORD, self::INCORRECT_CURRENT_PASSWORD);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość powinna być aktualnym hasłem użytkownika.', self::CURRENT_PASSWORD_FIELD);
        $I->click(self::SUBMIT);

        $I->fillField(self::NEW_PASSWORD, self::NEW_PASSWORD_VALUE);
        $I->fillField(self::NEW_PASSWORD_FIELD, self::NEW_PASSWORD_INCORRECT_VALUE);
        $I->click(self::SUBMIT);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość jest nieprawidłowa.', self::NEW_PASSWORD_FIELD);

        $I->fillField(self::NEW_PASSWORD_FIELD, self::NEW_PASSWORD_VALUE);
        $I->click(self::SUBMIT);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość jest nieprawidłowa.', self::NEW_PASSWORD_FIELD);

        $I->fillField(self::NEW_PASSWORD_FIELD, self::NEW_PASSWORD_VALUE);
        $I->fillField(self::NEW_PASSWORD_REPEAT_FIELD, self::REPEAT_PASSWORD_INCORRECT_VALUE);
        $I->click(self::SUBMIT);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość jest nieprawidłowa.', self::NEW_PASSWORD_FIELD);
    }
}
