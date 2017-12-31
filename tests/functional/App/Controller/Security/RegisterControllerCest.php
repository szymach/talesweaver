<?php

namespace App\Tests\Controller\Security;

use App\Entity\User;
use App\Entity\User\ActivationToken;
use App\Tests\FunctionalTester;

class RegisterControllerCest
{
    const FORM_URL = '/pl/registration';
    const FORM_SELECTOR = 'form[name="register"]';

    const EMAIL_FIELD = 'Email';
    const PASSWORD_FIELD = 'Hasło';
    const REPEAT_PASSWORD_FIELD = 'Powtórz hasło';

    const EMAIL = 'username@example.com';
    const PASSWORD = 'haslo123';
    const SHORT_PASSWORD = 'haslo';
    const NOT_MATCHING_PASSWORD = 'haslo321';
    const SUBMIT = 'Zarejestruj';

    const LOGIN_URL = '/pl/login';
    const DASHBOARD_URL = '/pl';

    public function registrationFormView(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->seeElement(self::FORM_SELECTOR);
        $I->see(self::EMAIL_FIELD);
        $I->see(self::PASSWORD_FIELD);
        $I->see(self::REPEAT_PASSWORD_FIELD);
    }

    public function validRegistrationAndLogin(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, self::EMAIL);
        $I->fillField(self::PASSWORD_FIELD, self::PASSWORD);
        $I->fillField(self::REPEAT_PASSWORD_FIELD, self::PASSWORD);
        $I->click(self::SUBMIT);

        $I->canSeeCurrentUrlEquals(self::LOGIN_URL);
        $I->seeInRepository(User::class, ['username' => self::EMAIL, 'active' => false]);
        $I->seeInRepository(ActivationToken::class);
        $I->canSeeAlert(
            'Pomyślnie zarejstrowano konto w aplikacji Bajkopisarz! Na podane'
            . ' konto email wysłano wiadomość do aktywacji konta.'
        );

        $I->fillField(self::EMAIL_FIELD, self::EMAIL);
        $I->fillField(self::PASSWORD_FIELD, self::PASSWORD);
        $I->canSeeCurrentUrlEquals(self::LOGIN_URL);
    }

    public function emptyRegistrationForm(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[username]');
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[password][first]');
    }

    public function invalidRegistrationForm(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, 'nie-email');
        $I->fillField(self::PASSWORD_FIELD, self::SHORT_PASSWORD);
        $I->fillField(self::REPEAT_PASSWORD_FIELD, self::SHORT_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie jest prawidłowym adresem email.', 'register[username]');
        $I->seeError('Ta wartość jest zbyt krótka. Powinna mieć 6 lub więcej znaków.', 'register[password][first]');

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::PASSWORD_FIELD, self::PASSWORD);
        $I->fillField(self::REPEAT_PASSWORD_FIELD, self::NOT_MATCHING_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[username]');
        $I->seeError('Ta wartość jest nieprawidłowa.', 'register[password][first]');

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::PASSWORD_FIELD, self::PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[username]');
        $I->seeError('Ta wartość jest nieprawidłowa.', 'register[password][first]');
    }
}
