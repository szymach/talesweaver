<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Controller\Chapter;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $chapter1 = new Chapter(Uuid::uuid4(), new ShortText('Title'), null, $I->getAuthor(true, 'user2@example.com'));
        $I->getEntityManager()->persist($chapter1);

        $chapter2 = new Chapter(Uuid::uuid4(), new ShortText('Title 2'), null, $I->getAuthor());
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
