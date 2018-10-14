<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Module\AuthorModule;

class LoginControllerCest
{
    public const FORM_URL = '/pl/login';
    public const DASHBOARD_URL = '/pl';
    public const REGISTER_URL = '/pl/registration';
    public const PASSWORD_RESET_URL = '/pl/reset-password/request';

    public const FORM_SELECTOR = 'form';
    public const EMAIL_FIELD = 'Email';
    public const PASSWORD_FIELD = 'Hasło';
    public const SUBMIT = 'Zaloguj';
    public const REGISTER = 'Rejestracja';
    public const PASSWORD_RESET = 'Resetowanie hasła';

    public const NONEXISTANT_EMAIL = 'email@nieistnieje.pl';
    public const INCORRECT_PASSWORD = 'zlehaslo123';

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

        $I->amOnPage(self::FORM_URL);
        $I->click(self::PASSWORD_RESET);
        $I->canSeeCurrentUrlEquals(self::PASSWORD_RESET_URL);
    }

    public function correctLogin(FunctionalTester $I)
    {
        $I->getAuthor();

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, AuthorModule::AUTHOR_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, AuthorModule::AUTHOR_PASSWORD);
        $I->click(self::SUBMIT);

        $I->canSeeCurrentUrlEquals(self::DASHBOARD_URL);
    }

    public function incorrectLogin(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, self::NONEXISTANT_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, AuthorModule::AUTHOR_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeErrorAlert('Użytkownik o podanej nazwie nie istnieje.');

        $I->getAuthor();

        $I->amOnPage(self::FORM_URL);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeErrorAlert('Użytkownik o podanej nazwie nie istnieje.');

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, AuthorModule::AUTHOR_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, self::INCORRECT_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeErrorAlert('Nieprawidłowe dane.');
    }

    public function inactiveUserLogin(FunctionalTester $I)
    {
        $I->getAuthor(false);

        $I->amOnPage(self::FORM_URL);
        $I->fillField(self::EMAIL_FIELD, AuthorModule::AUTHOR_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, AuthorModule::AUTHOR_PASSWORD);
        $I->click(self::SUBMIT);

        $I->canSeeCurrentUrlEquals(self::FORM_URL);
    }
}
