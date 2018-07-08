<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

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
