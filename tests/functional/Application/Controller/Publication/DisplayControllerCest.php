<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Publication;

use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class DisplayControllerCest
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Publication
     */
    private $publication;

    public function testPublicationDisplayPageResponse(FunctionalTester $I): void
    {
        $I->amOnPage("/pl/publication/display/{$this->publication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
        $I->see('Publikacja');
    }

    public function testPublicationPublicDisplayPageResponse(FunctionalTester $I): void
    {
        $I->amOnPage('/logout');
        $I->seeCurrentUrlEquals('/pl/login');

        $I->amOnPage("/pl/publication/display/{$this->publication->getId()->toString()}");
        $I->seeCurrentUrlEquals('/pl/login');

        $I->amOnPage("/pl/publication/display-public/{$this->publication->getId()->toString()}");
        $I->seeCurrentUrlEquals("/pl/publication/display-public/{$this->publication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
        $I->see('Publikacja');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Scena');
        $this->scene = $scene;
        $this->scene->setLocale('pl');

        /** @var Publication $publication */
        $publication = $I->haveCreatedAScenePublication($scene, 'Publikacja');
        $this->publication = $publication;
    }
}
