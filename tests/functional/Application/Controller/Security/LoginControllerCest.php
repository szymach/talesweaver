<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Module\AuthorModule;

class LoginControllerCest
{
    public function loginFormView(FunctionalTester $I)
    {
        $I->amOnPage('/pl/login');
        $I->seeElement('form');
        $I->see('Email');
        $I->see('Hasło');
        $I->see('Zaloguj');
        $I->see('Rejestracja');

        $I->click('Rejestracja');
        $I->canSeeCurrentUrlEquals('/pl/registration');

        $I->amOnPage('/pl/login');
        $I->click('Resetowanie hasła');
        $I->canSeeCurrentUrlEquals('/pl/reset-password/request');
    }

    public function correctLogin(FunctionalTester $I)
    {
        $I->getAuthor();

        $I->amOnPage('/pl/login');
        $I->fillField('Email', AuthorModule::AUTHOR_EMAIL);
        $I->fillField('Hasło', AuthorModule::AUTHOR_PASSWORD);
        $I->click('Zaloguj');

        $I->canSeeCurrentUrlEquals('/pl');
    }

    public function incorrectLogin(FunctionalTester $I)
    {
        $I->amOnPage('/pl/login');
        $I->fillField('Email', 'email@nieistnieje.pl');
        $I->fillField('Hasło', AuthorModule::AUTHOR_PASSWORD);
        $I->click('Zaloguj');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->seeErrorAlert('Użytkownik o podanej nazwie nie istnieje.');

        $I->getAuthor();

        $I->amOnPage('/pl/login');
        $I->click('Zaloguj');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->seeErrorAlert('Użytkownik o podanej nazwie nie istnieje.');

        $I->amOnPage('/pl/login');
        $I->fillField('Email', AuthorModule::AUTHOR_EMAIL);
        $I->fillField('Hasło', 'zlehaslo123');
        $I->click('Zaloguj');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->seeErrorAlert('Nieprawidłowe dane.');
    }

    public function inactiveUserLogin(FunctionalTester $I)
    {
        $I->getAuthor(AuthorModule::AUTHOR_EMAIL, AuthorModule::AUTHOR_PASSWORD, false);

        $I->amOnPage('/pl/login');
        $I->fillField('Email', AuthorModule::AUTHOR_EMAIL);
        $I->fillField('Hasło', AuthorModule::AUTHOR_PASSWORD);
        $I->click('Zaloguj');

        $I->canSeeCurrentUrlEquals('/pl/login');
    }
}
