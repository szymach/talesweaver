<?php

namespace Tests\AppBundle\Controller\Book;

use AppBundle\Entity\Book;
use FunctionalTester;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    const LIST_URL = '/pl/book/list';
    const TITLE_PL = 'Tytuł nowej książki';

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
    }
}
