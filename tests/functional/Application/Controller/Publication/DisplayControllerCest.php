<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Publication;

use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class DisplayControllerCest
{
    /**
     * @var Publication
     */
    private $visiblePublication;

    /**
     * @var Publication
     */
    private $notVisiblePublication;

    public function testPublicationDisplayPageResponse(FunctionalTester $I): void
    {
        $I->amOnPage("/pl/publication/display/{$this->notVisiblePublication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
        $I->see('Publikacja 1');

        $I->amOnPage("/pl/publication/display/{$this->visiblePublication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
        $I->see('Publikacja 2');
    }

    public function testPublicationPublicDisplayPageResponse(FunctionalTester $I): void
    {
        $I->amOnPage('/logout');
        $I->seeCurrentUrlEquals('/pl/login');

        $I->amOnPage("/pl/publication/public/{$this->notVisiblePublication->getId()->toString()}");
        $I->seeResponseCodeIs(404);

        $I->amOnPage("/pl/publication/public/{$this->visiblePublication->getId()->toString()}");
        $I->seeCurrentUrlEquals("/pl/publication/public/{$this->visiblePublication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
        $I->see('Publikacja 2');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Scena');
        $scene->setLocale('pl');

        /** @var Publication $notVisiblePublication */
        $notVisiblePublication = $I->haveCreatedAScenePublication($scene, 'Publikacja 1', false);
        $this->notVisiblePublication = $notVisiblePublication;

        /** @var Publication $visiblePublication */
        $visiblePublication = $I->haveCreatedAScenePublication($scene, 'Publikacja 2', true);
        $this->visiblePublication = $visiblePublication;
    }
}
