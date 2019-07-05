<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Location;

use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class FormControllerCest
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var string
     */
    private $sceneId;

    public function testAjaxEditSubmit(FunctionalTester $I): void
    {
        $I->amOnPage("/pl/scene/edit/{$this->sceneId}");
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/location/new/{$this->sceneId}");

        $I->sendPOST("/pl/location/new/{$this->sceneId}", [
            'create' => [
                'name' => 'Miejsce 1',
                '_token' => $I->fetchTokenFromAjaxResponse('#create__token')
            ]
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeLocationDoesNotExist('Miejsce 1', $this->scene);

        $I->sendPOST("/pl/location/new/{$this->sceneId}", [
            'create' => [
                'name' => 'Miejsce 2',
                '_token' => $I->fetchTokenFromAjaxResponse('#create__token')
            ]
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeLocationDoesNotExist('Miejsce 1', $this->scene);

        $I->sendPOST("/pl/location/new/{$this->sceneId}", [
            'create' => [
                'name' => 'Miejsce 3',
                '_token' => $I->fetchTokenFromAjaxResponse('#create__token')
            ]
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeLocationExists('Miejsce 3', $this->scene);
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        $book = $I->haveCreatedABook('Książka');

        $chapter1 = $I->haveCreatedAChapter('Rozdział 1', $book);
        $chapter2 = $I->haveCreatedAChapter('Rozdział 2', $book);

        $scene1 = $I->haveCreatedAScene('Scena 1', $chapter1);
        $scene2 = $I->haveCreatedAScene('Scena 2', $chapter1);
        $scene3 = $I->haveCreatedAScene('Scena 3', $chapter2);

        $I->haveCreatedALocation('Miejsce 1', $scene1);
        $I->haveCreatedALocation('Miejsce 2', $scene3);

        $this->scene = $scene2;
        $this->sceneId = $scene2->getId()->toString();
    }
}
