<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

class ListControllerCest
{
    public function testBookList(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/book/list');
        $I->see('Nie masz jeszcze żadnej książki', 'h4');
        $I->see('Dodaj nową!', 'a[href="/pl/book/create"]');
        $I->cantSeeElement('table');

        $I->haveCreatedABook('Książka');
        $I->amOnPage('/pl/book/list');
        $I->see('Książka', 'td');
        $I->seeElement('a[title="Edycja"]');
        $I->seeElement('a[title="Usuń"]');
        $I->seeElement('a[title="Nowa"]');

        $I->click('a[title="Edycja"]');
        $I->canSeeInTitle('Książka - edycja');
    }
}
