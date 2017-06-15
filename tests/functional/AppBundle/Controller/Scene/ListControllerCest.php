<?php

namespace Tests\AppBundle\Controller\Scene;

use FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage('/pl/scene/list');
        $I->seeElement('table');
        $I->see('Tytuł', ['css' => 'th']);
        $I->see('Akcje', 'th');
    }
}
