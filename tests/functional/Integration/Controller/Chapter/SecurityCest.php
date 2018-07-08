<?php

declare(strict_types=1);

namespace Integration\Tests\Controller\Chapter;

use Integration\Tests\FunctionalTester;
use Domain\Chapter;
use Ramsey\Uuid\Uuid;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $user1 = $I->getUser(true, 'user2@example.com');
        $chapter1 = new Chapter(Uuid::uuid4(), 'Title', null, $user1);
        $I->getEntityManager()->persist($chapter1);

        $user2 = $I->getUser();
        $chapter2 = new Chapter(Uuid::uuid4(), 'Chapter', null, $user2);
        $I->getEntityManager()->persist($chapter2);

        $I->getEntityManager()->flush();

        $I->loginAsUser(); // as user2

        $I->amOnPage(sprintf('/pl/chapter/edit/%s', $chapter2->getId()->toString()));
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage(sprintf('/pl/chapter/edit/%s', $chapter1->getId()->toString()));
        $I->canSeeResponseCodeIs(404);

        $I->amOnPage(sprintf('/pl/chapter/display/%s', $chapter2->getId()->toString()));
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage(sprintf('/pl/chapter/display/%s', $chapter1->getId()->toString()));
        $I->canSeeResponseCodeIs(404);
    }
}
