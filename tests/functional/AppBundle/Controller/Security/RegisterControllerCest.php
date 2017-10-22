<?php

namespace Tests\AppBundle\Controller\Security;

use AppBundle\Entity\User;
use FunctionalTester;

class RegisterControllerCest
{
    const FORM_URL = '/pl/registration';
    const FORM_SELECTOR = 'form[name="register"]';
    const EMAIL = 'username@example.com';
    const PASSWORD = 'haslo123';
    const SHORT_PASSWORD = 'haslo';
    const NOT_MATCHING_PASSWORD = 'haslo321';
    const SUBMIT = 'Zarejestruj';

    const LOGIN_URL = '/pl/login';
    const DASHBOARD_URL = '/pl';

    const ERROR_SELECTOR = '.help-block .list-unstyled li';

    public function registrationFormView(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->seeElement(self::FORM_SELECTOR);
        $I->see('Email');
        $I->see('Hasło');
        $I->see('Powtórz hasło');
    }

    public function validRegistrationAndLogin(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->fillField('Email', self::EMAIL);
        $I->fillField('Hasło', self::PASSWORD);
        $I->fillField('Powtórz hasło', self::PASSWORD);
        $I->click(self::SUBMIT);

        $I->canSeeCurrentUrlEquals(self::LOGIN_URL);
        $I->seeInRepository(User::class, ['username' => self::EMAIL]);

        $I->fillField('Email', self::EMAIL);
        $I->fillField('Hasło', self::PASSWORD);
        $I->amOnPage(self::DASHBOARD_URL);
    }

    public function emptyRegistrationForm(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfElements(self::ERROR_SELECTOR, 2);
        $this->iSeeError($I, 'Ta wartość nie powinna być pusta.', '[username]');
        $this->iSeeError($I, 'Ta wartość nie powinna być pusta.', '[password][first]');
    }

    public function invalidRegistrationForm(FunctionalTester $I)
    {
        $I->amOnPage(self::FORM_URL);
        $I->fillField('Email', 'nie-email');
        $I->fillField('Hasło', self::SHORT_PASSWORD);
        $I->fillField('Powtórz hasło', self::SHORT_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfElements(self::ERROR_SELECTOR, 2);
        $this->iSeeError($I, 'Ta wartość nie jest prawidłowym adresem email.', '[username]');
        $this->iSeeError($I, 'Ta wartość jest zbyt krótka. Powinna mieć 6 lub więcej znaków.', '[password][first]');

        $I->amOnPage(self::FORM_URL);
        $I->fillField('Hasło', self::PASSWORD);
        $I->fillField('Powtórz hasło', self::NOT_MATCHING_PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfElements(self::ERROR_SELECTOR, 2);
        $this->iSeeError($I, 'Ta wartość nie powinna być pusta.', '[username]');
        $this->iSeeError($I, 'Ta wartość jest nieprawidłowa.', '[password][first]');

        $I->amOnPage(self::FORM_URL);
        $I->fillField('Hasło', self::PASSWORD);
        $I->click(self::SUBMIT);
        $I->canSeeCurrentUrlEquals(self::FORM_URL);
        $I->seeNumberOfElements(self::ERROR_SELECTOR, 2);
        $this->iSeeError($I, 'Ta wartość nie powinna być pusta.', '[username]');
        $this->iSeeError($I, 'Ta wartość jest nieprawidłowa.', '[password][first]');
    }

    private function iSeeError(FunctionalTester $I, string $content, string $field): void
    {
        $I->see(
            $content,
            sprintf('input[name="register%s"] + %s', $field, self::ERROR_SELECTOR)
        );
    }
}
