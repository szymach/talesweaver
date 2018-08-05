<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Book;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $user1 = $I->getUser(true, 'user2@example.com');
        $book1 = new Book(Uuid::uuid4(), new ShortText('Title'), $user1->getAuthor());
        $I->getEntityManager()->persist($book1);

        $user2 = $I->getUser();
        $book2 = new Book(Uuid::uuid4(), new ShortText('Title2'), $user2->getAuthor());
        $I->getEntityManager()->persist($book2);

        $I->getEntityManager()->flush();

        $I->loginAsUser(); // as user2
        $I->amOnPage(sprintf('/pl/book/edit/%s', $book2->getId()->toString()));
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage(sprintf('/pl/book/edit/%s', $book1->getId()->toString()));
        $I->canSeeResponseCodeIs(404);
    }
}
