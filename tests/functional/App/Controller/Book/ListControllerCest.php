<?php

namespace App\Tests\Controller\Book;

use App\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/book/list');
        $I->seeElement('table');
    }
}
