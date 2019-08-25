<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class DeleteControllerCest
{
    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();

        /** @var Scene $scene */
        $scene = $I->haveCreatedAScene('Tytuł nowej sceny');

        $I->amOnPage('/pl/scene/list');
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');

        $I->canSeeCurrentUrlEquals('/pl/scene/list');
        $I->seeSceneHasBeenRemoved($scene->getId());
        $I->canSeeAlert('Scena "Tytuł nowej sceny" została usunięta.');
    }
}
