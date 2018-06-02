<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\FunctionalTester;

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