<?php

namespace App\Tests\Controller\Chapter;

use App\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/list');
        $I->seeElement('h4');
        $I->see('Nie masz jeszcze żadnego nieprzypisanego do książki rozdziału.');
    }
}
