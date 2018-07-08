<?php

declare(strict_types=1);

namespace Integration\Tests\Controller\Book;

use Integration\Tests\FunctionalTester;

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
