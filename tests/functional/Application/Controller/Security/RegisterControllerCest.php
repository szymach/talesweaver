<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

class RegisterControllerCest
{
    public function registrationFormView(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/registration');
        $I->seeElement('form[name="register"]');
        $I->see('Email');
        $I->see('Hasło');
        $I->see('Powtórz hasło');
        $I->see('Imię');
        $I->see('Nazwisko');
    }

    public function validRegistrationAndLogin(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/registration');
        $I->fillField('Email', 'email@example.com');
        $I->fillField('Hasło', 'haslo123');
        $I->fillField('Powtórz hasło', 'haslo123');
        $I->fillField('Imię', 'Imię autora');
        $I->fillField('Nazwisko', 'Nazwisko autora');
        $I->click('Zarejestruj');

        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->seeNewAuthorHasBeenCreated('email@example.com', 'Imię autora', 'Nazwisko autora');
        $I->canSeeAlert(
            'Pomyślnie zarejstrowano konto w aplikacji Bajkopisarz! Na podane '
            . 'konto email wysłano wiadomość z instrukcją do aktywacji konta.'
        );

        $I->fillField('Email', 'email@example.com');
        $I->fillField('Hasło', 'haslo123');
        $I->canSeeCurrentUrlEquals('/pl/login');
    }

    public function emptyRegistrationForm(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/registration');
        $I->click('Zarejestruj');
        $I->canSeeCurrentUrlEquals('/pl/registration');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[email]');
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[password][first]');
    }

    public function invalidRegistrationForm(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/registration');
        $I->fillField('Email', 'nie-email');
        $I->fillField('Hasło', 'haslo');
        $I->fillField('Powtórz hasło', 'haslo');
        $I->click('Zarejestruj');
        $I->canSeeCurrentUrlEquals('/pl/registration');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie jest prawidłowym adresem email.', 'register[email]');
        $I->seeError('Ta wartość jest zbyt krótka. Powinna mieć 6 lub więcej znaków.', 'register[password][first]');

        $I->amOnPage('/pl/registration');
        $I->fillField('Hasło', 'haslo123');
        $I->fillField('Powtórz hasło', 'haslo321');
        $I->click('Zarejestruj');
        $I->canSeeCurrentUrlEquals('/pl/registration');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[email]');
        $I->seeError('Ta wartość jest nieprawidłowa.', 'register[password][first]');

        $I->amOnPage('/pl/registration');
        $I->fillField('Hasło', 'haslo123');
        $I->click('Zarejestruj');
        $I->canSeeCurrentUrlEquals('/pl/registration');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość nie powinna być pusta.', 'register[email]');
        $I->seeError('Ta wartość jest nieprawidłowa.', 'register[password][first]');
    }
}
