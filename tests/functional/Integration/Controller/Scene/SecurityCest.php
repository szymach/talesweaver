<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Scene;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $user1 = $I->getUser(true, 'user2@example.com');
        $scene1 = new Scene(Uuid::uuid4(), 'Title', null, $user1);
        $I->getEntityManager()->persist($scene1);

        $user2 = $I->getUser();
        $scene2 = new Scene(Uuid::uuid4(), 'Scene', null, $user2);
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
