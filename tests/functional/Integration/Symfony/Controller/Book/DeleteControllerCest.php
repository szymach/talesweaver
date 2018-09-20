<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Controller\Book;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    private const LIST_URL = '/pl/book/list';
    private const TITLE_PL = 'Tytuł nowej książki';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Book($id, new ShortText(self::TITLE_PL), $I->getAuthor()));
        $I->seeInRepository(Book::class, ['id' => $id]);
        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Book::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Książka "%s" została usunięta.', self::TITLE_PL));
    }
}