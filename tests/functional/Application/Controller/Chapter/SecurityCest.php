<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Tests\FunctionalTester;

class SecurityCest
{
    public function verifyAccess(FunctionalTester $I)
    {
        $I->loginAsUser('user1@example.com');
        $chapter1Id = $I->haveCreatedAChapter('Title')->getId()->toString();

        $I->loginAsUser('user2@example.com');
        $chapter2Id = $I->haveCreatedAChapter('Title 2')->getId()->toString();

        $I->amOnPage("/pl/chapter/edit/{$chapter2Id}");
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage("/pl/chapter/display/{$chapter2Id}");
        $I->canSeeResponseCodeIs(200);

        $I->amOnPage("/pl/chapter/edit/{$chapter1Id}");
        $I->canSeeResponseCodeIs(404);

        $I->amOnPage("/pl/chapter/display/{$chapter1Id}");
        $I->canSeeResponseCodeIs(404);
    }
}
