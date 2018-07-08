<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Book;

use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Domain\Book;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    private const LIST_URL = '/pl/book/list';
    private const TITLE_PL = 'Tytuł nowej książki';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Book($id, self::TITLE_PL, $I->getUser()));
        $I->seeInRepository(Book::class, ['id' => $id]);
        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Book::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Książka "%s" została usunięta.', self::TITLE_PL));
    }
}
