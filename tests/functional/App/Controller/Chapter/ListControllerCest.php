<?php

namespace App\Tests\Controller\Chapter;

use App\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/list');
        $I->seeElement('table');
    }
}
