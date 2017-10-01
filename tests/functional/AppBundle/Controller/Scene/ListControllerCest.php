<?php

namespace Tests\AppBundle\Controller\Scene;

use FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/scene/list');
        $I->seeElement('table');
    }
}
