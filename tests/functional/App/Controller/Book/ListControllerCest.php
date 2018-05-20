<?php

declare(strict_types=1);

namespace App\Tests\Controller\Book;

use App\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/book/list');
        $I->seeElement('h4');
        $I->see('Nie masz jeszcze żadnej książki');
    }
}
