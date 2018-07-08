<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Security;

use Talesweaver\Domain\User;
use Talesweaver\Domain\User\ActivationToken;
use Talesweaver\Tests\FunctionalTester;

class RegisterControllerCest
{
    private const FORM_URL = '/pl/registration';
    private const FORM_SELECTOR = 'form[name="register"]';

    private const EMAIL_FIELD = 'Email';
    private const PASSWORD_FIELD = 'Hasło';
    private const REPEAT_PASSWORD_FIELD = 'Powtórz hasło';

    private const EMAIL = 'username@example.com';
    private const PASSWORD = 'haslo123';
    private const SHORT_PASSWORD = 'haslo';
    private const NOT_MATCHING_PASSWORD = 'haslo321';
    private const SUBMIT = 'Zarejestruj';

    private const LOGIN_URL = '/pl/login';
    private const DASHBOARD_URL = '/pl';

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
