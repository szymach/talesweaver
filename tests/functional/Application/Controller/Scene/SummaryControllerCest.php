<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class SummaryControllerCest
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var string
     */
    private $sceneId;

    public function testResponse(FunctionalTester $I): void
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/scene/summary/{$this->sceneId}");

        $I->seeResponseCodeIs(200);
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        $scene = $I->haveCreatedAScene('Scene');

        $character1 = $I->haveCreatedACharacter('Character 1', $scene);
        $character2 = $I->haveCreatedACharacter('Character 2', $scene);
        $character3 = $I->haveCreatedACharacter('Character 3', $scene);
        $character4 = $I->haveCreatedACharacter('Character 4', $scene);

        $item1 = $I->haveCreatedAnItem('Item 1', $scene);
        $item2 = $I->haveCreatedAnItem('Item 2', $scene);
        $item3 = $I->haveCreatedAnItem('Item 3', $scene);
        $item4 = $I->haveCreatedAnItem('Item 4', $scene);

        $location1 = $I->haveCreatedALocation('Location 1', $scene);
        $location2 = $I->haveCreatedALocation('Location 2', $scene);

        $I->haveCreatedAnEvent('Event 1', $scene, $location1, [$character1, $character2], [$item1, $item2]);
        $I->haveCreatedAnEvent('Event 2', $scene, $location2, [$character3, $character4], [$item3, $item4]);

        $this->scene = $scene;
        $this->sceneId = $scene->getId()->toString();
    }
}
