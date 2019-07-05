<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Character;

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
        $I->sendGET("/pl/character/new/{$this->sceneId}");

        $I->sendPOST("/pl/character/new/{$this->sceneId}", [
            'create' => [
                'name' => 'Postać 1',
                '_token' => $I->fetchTokenFromAjaxResponse('#create__token')
            ]
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeCharacterDoesNotExist('Postać 1', $this->scene);

        $I->sendPOST("/pl/character/new/{$this->sceneId}", [
            'create' => [
                'name' => 'Postać 2',
                '_token' => $I->fetchTokenFromAjaxResponse('#create__token')
            ]
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeCharacterDoesNotExist('Postać 1', $this->scene);

        $I->sendPOST("/pl/character/new/{$this->sceneId}", [
            'create' => [
                'name' => 'Postać 3',
                '_token' => $I->fetchTokenFromAjaxResponse('#create__token')
            ]
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeCharacterExists('Postać 3', $this->scene);
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

        $I->haveCreatedACharacter('Postać 1', $scene1);
        $I->haveCreatedACharacter('Postać 2', $scene3);

        $this->scene = $scene2;
        $this->sceneId = $scene2->getId()->toString();
    }
}
