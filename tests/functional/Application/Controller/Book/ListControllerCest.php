<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

final class ListControllerCest
{
    public function testBookList(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/book/list');
        $I->see('Nie znaleziono żadnej książki', 'h4');
        $I->see('Dodaj nową!', 'a[href="/pl/book/create"]');
        $I->cantSeeElement('table');

        $I->haveCreatedABook('Książka 1');
        $I->haveCreatedABook('Książka 2');
        $I->amOnPage('/pl/book/list');
        $I->see('Książka 1', 'td');
        $I->see('Książka 2', 'td');

        $I->click('Sortuj malejąco', 'th:first-child a');
        $I->see('Książka 2', 'tr:first-child td');

        $I->click('Sortuj rosnąco', 'th:first-child a');
        $I->see('Książka 1', 'tr:first-child td');

        $I->seeElement('a[title="Edycja"]');
        $I->seeElement('a[title="Usuń"]');
        $I->seeElement('a[title="Nowa"]');

        $I->click('a[title="Edycja"]');
        $I->canSeeInTitle('Książka 1 - edycja');
    }
}
