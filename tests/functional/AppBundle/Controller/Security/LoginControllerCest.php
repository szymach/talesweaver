<?php

namespace Tests\AppBundle\Controller\Security;

use FunctionalTester;

class LoginControllerCest
{
    const FORM_URL = '/pl/login';
    const DASHBOARD_URL = '/pl';
    const REGISTER_URL = '/pl/registration';

    const FORM_SELECTOR = 'form';
    const EMAIL_FIELD = 'Email';
    const PASSWORD_FIELD = 'Hasło';
    const SUBMIT = 'Zaloguj';
    const REGISTER = 'Rejestracja';

    const NONEXISTANT_EMAIL = 'email@nieistnieje.pl';
    const INCORRECT_PASSWORD = 'zlehaslo123';

    public function loginFormView(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->seeElement(self::FORM_SELECTOR);
        $I->see(self::EMAIL_FIELD);
        $I->see(self::PASSWORD_FIELD);
        $I->see(self::SUBMIT);
        $I->see(self::REGISTER);

        $I->click(self::REGISTER);
        $I->canSeeCurrentUrlEquals(self::REGISTER_URL);
    }

    public function correctLogin(FunctionalTester $I)
    {
        $I->getUser();

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, FunctionalTester::USER_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, FunctionalTester::USER_PASSWORD);
        $I->click(self::SUBMIT);

        $I->canSeeCurrentUrlEquals(self::DASHBOARD_URL);
    }

    public function incorrectLogin(FunctionalTester $I)
    {
        $I->getUser();

        $I->amOnPage(self::FORM_URL);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeErrorAlert('Użytkownik o podanej nazwie nie istnieje.');

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, FunctionalTester::USER_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, self::INCORRECT_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeErrorAlert('Nieprawidłowe dane.');

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, self::NONEXISTANT_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, FunctionalTester::USER_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeErrorAlert('Użytkownik o podanej nazwie nie istnieje.');
    }

    public function inactiveUserLogin(FunctionalTester $I)
    {
        $I->getUser(false);

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, FunctionalTester::USER_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, FunctionalTester::USER_PASSWORD);
        $I->click(self::SUBMIT);

        $I->canSeeCurrentUrlEquals(self::FORM_URL);
    }
}
