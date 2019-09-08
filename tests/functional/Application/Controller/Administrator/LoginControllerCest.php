<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Administrator;

use Talesweaver\Tests\FunctionalTester;

final class LoginControllerCest
{
    public function testIncorrectLogin(FunctionalTester $I): void
    {
        $I->amOnPage('/administration');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/administration/login');

        $I->fillField('Email', 'admin@example.com');
        $I->fillField('Hasło', 'password');
        $I->click('Zaloguj');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/administration/login');

        $I->grabAdministrator('admin@example.com', 'password');

        $I->fillField('Email', 'admin@example.com');
        $I->fillField('Hasło', 'wrong_password');
        $I->click('Zaloguj');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/administration/login');

        $I->fillField('Email', 'admin@example.');
        $I->fillField('Hasło', 'password');
        $I->click('Zaloguj');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/administration/login');
    }

    public function testCorrectLogin(FunctionalTester $I): void
    {
        $I->grabAdministrator('admin@example.com', 'password');

        $I->amOnPage('/administration');

        $I->fillField('Email', 'admin@example.com');
        $I->fillField('Hasło', 'password');
        $I->click('Zaloguj');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/administration');
    }
}
