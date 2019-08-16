<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class IncreasePositionControllerCest
{
    public function testAction(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Chapter $chapter */
        $chapter = $I->haveCreatedAChapter('Rozdział');

        /** @var Scene $scene1 */
        $scene1 = $I->haveCreatedAScene('Rozdział 1', $chapter);
        /** @var Scene $scene2 */
        $scene2 = $I->haveCreatedAScene('Rozdział 2', $chapter);

        $I->refreshEntities([$scene1, $scene2]);

        $I->assertEquals(1, $scene1->getPosition());
        $I->assertEquals(0, $scene2->getPosition());

        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/scene/position/single/increase/{$scene2->getId()->toString()}");

        $I->seeResponseCodeIs(200);

        $I->refreshEntities([$scene1, $scene2]);

        $I->assertEquals(0, $scene1->getPosition());
        $I->assertEquals(1, $scene2->getPosition());
    }
}
