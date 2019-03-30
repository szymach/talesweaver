<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller;

use Talesweaver\Tests\FunctionalTester;

class DashboardControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl');
        $I->seeElement('h3');
        $I->see('Witaj w aplikacji Bajkopisarz!');
        $I->see('Książki');
        $I->see('Rozdziały');
        $I->see('Sceny');
    }
}
