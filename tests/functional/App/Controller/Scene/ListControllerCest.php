<?php

namespace App\Tests\Controller\Scene;

use App\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/scene/list');
        $I->seeElement('table');
    }
}
