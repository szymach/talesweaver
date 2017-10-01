<?php

namespace Tests\AppBundle\Controller\Chapter;

use FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/list');
        $I->seeElement('table');
    }
}
