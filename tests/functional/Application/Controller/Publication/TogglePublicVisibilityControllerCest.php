<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Publication;

use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class TogglePublicVisibilityControllerCest
{
    public function testTogglingPublicationVisibility(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Scena');
        $scene->setLocale('pl');

        /** @var Publication $publication */
        $publication = $I->haveCreatedAScenePublication($scene, 'Publikacja', false);

        $I->amOnPage("/pl/publication/public/{$publication->getId()->toString()}");
        $I->seeCurrentUrlEquals("/pl/publication/public/{$publication->getId()->toString()}");
        $I->seeResponseCodeIs(404);

        $I->amOnPage('/');
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/publication/toggle/{$publication->getId()->toString()}");
        $I->seeResponseCodeIs(200);

        $I->amOnPage("/pl/publication/public/{$publication->getId()->toString()}");
        $I->seeCurrentUrlEquals("/pl/publication/public/{$publication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
    }
}
