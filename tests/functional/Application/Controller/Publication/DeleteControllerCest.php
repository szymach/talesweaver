<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Publication;

use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class DeleteControllerCest
{
    public function testPublicationDeletion(FunctionalTester $I): void
    {
        $I->loginAsUser();
        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Scena');
        $scene->setLocale('pl');

        /** @var Publication $publication */
        $publication = $I->haveCreatedAScenePublication($scene);

        $I->amOnPage("/pl/scene/edit/{$scene->getId()->toString()}");

        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/publication/delete/{$publication->getId()->toString()}");
        $I->seeResponseCodeIs(200);

        /** @var Scene $scene */
        $scene = $I->grabSceneByTitle('Scena');
        $I->assertNull($scene->getCurrentPublication('pl'));
    }
}
