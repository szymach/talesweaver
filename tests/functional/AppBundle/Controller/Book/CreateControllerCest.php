<?php

namespace Tests\AppBundle\Controller\Book;

use FunctionalTester;

class CreateControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage('/pl/book/create');
        $I->see('Nowa książka', 'h1');
    }
}
