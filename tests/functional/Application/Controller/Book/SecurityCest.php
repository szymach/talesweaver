<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $I->loginAsUser('user1@example.com');
        $book1Id = $I->haveCreatedABook('Title')->getId();

        $I->loginAsUser('user2@example.com');
        $book2Id = $I->haveCreatedABook('Title 2')->getId();

        $I->amOnPage("/pl/book/edit/{$book2Id->toString()}");
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage("/pl/book/edit/{$book1Id->toString()}");
        $I->canSeeResponseCodeIs(404);
    }
}
