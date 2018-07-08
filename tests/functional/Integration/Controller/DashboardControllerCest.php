<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller;

use Talesweaver\Tests\FunctionalTester;

class DashboardControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl');
        $I->seeElement('h3');
        $I->see('Witaj w aplikacji Bajkopisarz!');
        $I->see('Start');
        $I->see('Książki');
        $I->see('Rozdziały');
        $I->see('Sceny');
    }
}
