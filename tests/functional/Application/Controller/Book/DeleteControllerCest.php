<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    public function testBookDeletetion(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $bookId = $I->haveCreatedABook('Tytuł nowej książki');
        $I->amOnPage('/pl/book/list');
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals('/pl/book/list');
        $I->seeBookHasBeenRemoved($bookId);
        $I->canSeeAlert('Książka "Tytuł nowej książki" została usunięta.');
    }
}
