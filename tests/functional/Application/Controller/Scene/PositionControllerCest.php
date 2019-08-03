<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class PositionControllerCest
{
    public function testAction(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Chapter $chapter */
        $chapter = $I->haveCreatedAChapter('Rozdział');

        /** @var Scene $scene1 */
        $scene1 = $I->haveCreatedAScene('Scena 1', $chapter);
        $I->assertEquals(0, $scene1->getPosition());

        /** @var Scene $scene2 */
        $scene2 = $I->haveCreatedAScene('Scena 2', $chapter);
        $I->assertEquals(0, $scene2->getPosition());

        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');

        $I->sendAjaxPostRequest('/pl/scene/position/multiple', [
            ['id' => $scene1->getId()->toString(), 'position' => 1],
            ['id' => $scene2->getId()->toString(), 'position' => 0]
        ]);

        $I->seeResponseCodeIs(200);

        $scene1 = $I->grabSceneByTitle('Scena 1');
        $I->assertEquals(1, $scene1->getPosition());
        $scene2 = $I->grabSceneByTitle('Scena 2');
        $I->assertEquals(0, $scene2->getPosition());
    }
}
