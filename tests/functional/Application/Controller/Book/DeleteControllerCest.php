<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $bookId = $I->haveCreatedABook('Tytuł nowej książki');
        $I->amOnPage('/pl/book/list');
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals('/pl/book/list');
        $I->seeBookHasBeenRemoved($bookId);
        $I->canSeeAlert(sprintf('Książka "%s" została usunięta.', 'Tytuł nowej książki'));
    }
}
