<?php

namespace Tests\AppBundle\Controller\Chapter;

use FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage('/pl/chapter/list');
        $I->seeElement('table');
        $I->see('Tytuł', ['css' => 'th']);
        $I->see('Akcje', 'th');
    }
}
