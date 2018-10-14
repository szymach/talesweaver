<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $scene1 = new Scene(Uuid::uuid4(), new ShortText('Tytuł'), null, $I->getAuthor('user2@example.com'));
        $I->getEntityManager()->persist($scene1);

        $scene2 = new Scene(Uuid::uuid4(), new ShortText('Scena'), null, $I->getAuthor());
        $I->getEntityManager()->persist($scene2);

        $I->getEntityManager()->flush();

        $I->loginAsUser(); // as user2

        $I->amOnPage(sprintf('/pl/scene/edit/%s', $scene2->getId()->toString()));
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage(sprintf('/pl/scene/edit/%s', $scene1->getId()->toString()));
        $I->canSeeResponseCodeIs(404);

        $I->amOnPage(sprintf('/pl/scene/display/%s', $scene2->getId()->toString()));
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage(sprintf('/pl/scene/display/%s', $scene1->getId()->toString()));
        $I->canSeeResponseCodeIs(404);
    }
}
