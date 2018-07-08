<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Book;

use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Domain\Book;
use Ramsey\Uuid\Uuid;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $user1 = $I->getUser(true, 'user2@example.com');
        $book1 = new Book(Uuid::uuid4(), 'Title', $user1);
        $I->getEntityManager()->persist($book1);

        $user2 = $I->getUser();
        $book2 = new Book(Uuid::uuid4(), 'Book', $user2);
        $I->getEntityManager()->persist($book2);

        $I->getEntityManager()->flush();

        $I->loginAsUser(); // as user2
        $I->amOnPage(sprintf('/pl/book/edit/%s', $book2->getId()->toString()));
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage(sprintf('/pl/book/edit/%s', $book1->getId()->toString()));
        $I->canSeeResponseCodeIs(404);
    }
}
