<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class PublishControllerCest
{
    public function testScenePublication(FunctionalTester $I): void
    {
        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Scena');
        $sceneId = $scene->getId()->toString();

        $I->amOnPage("/pl/scene/edit/{$sceneId}");
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/scene/publish/{$sceneId}");
        $I->seeResponseCodeIs(200);

        $I->sendPOST("/pl/scene/publish/{$sceneId}", [
            'publish' => [
                'title' => 'Publikacja',
                'visible' => 1,
                '_token' => $I->fetchTokenFromAjaxResponse('#publish__token')
            ]
        ]);
        $I->seeResponseCodeIs(200);

        /** @var Scene $scene */
        $scene = $I->grabSceneByTitle('Scena');
        $currentPublication = $scene->getCurrentPublication('pl');
        $I->assertNotNull($currentPublication);
        $I->assertTrue($currentPublication->isVisible());
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();
    }
}
