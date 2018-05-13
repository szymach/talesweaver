<?php

namespace App\Tests\Controller\Scene;

use App\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/scene/list');
        $I->seeElement('h4');
        $I->see('Nie masz jeszcze żadnej nieprzypisanej do rozdziału sceny.');
    }
}
