<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $I->loginAsUser('user1@example.com');
        $scene1Id = $I->haveCreatedAScene('Title')->getId()->toString();

        $I->loginAsUser('user2@example.com');
        $scene2Id = $I->haveCreatedAScene('Title 2')->getId()->toString();

        $I->amOnPage("/pl/scene/edit/{$scene2Id}");
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage("/pl/scene/display/{$scene2Id}");
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage("/pl/scene/edit/{$scene1Id}");
        $I->canSeeResponseCodeIs(404);

        $I->amOnPage("/pl/scene/display/{$scene1Id}");
        $I->canSeeResponseCodeIs(404);
    }
}
