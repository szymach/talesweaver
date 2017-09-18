<?php

namespace Tests\AppBundle\Controller\Book;

use FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage('/pl/book/list');
        $I->seeElement('table');
    }
}
