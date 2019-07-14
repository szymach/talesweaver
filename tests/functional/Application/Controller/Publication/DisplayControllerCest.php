<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Publication;

use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class DisplayControllerCest
{
    public function testResponse(FunctionalTester $I): void
    {
        $I->loginAsUser();
        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Scena');
        $scene->setLocale('pl');

        /** @var Publication $publication */
        $publication = $I->haveCreatedAScenePublication($scene, 'Publikacja');

        $I->amOnPage("/pl/publication/display/{$publication->getId()->toString()}");
        $I->seeResponseCodeIs(200);
        $I->see('Publikacja');
    }
}
