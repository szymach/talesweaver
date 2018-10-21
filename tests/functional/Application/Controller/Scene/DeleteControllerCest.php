<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Scene($id, new ShortText('Tytuł nowej sceny'), null, $I->getAuthor()));
        $I->seeInRepository(Scene::class, ['id' => $id]);

        $I->amOnPage('/pl/scene/list');
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');

        $I->canSeeCurrentUrlEquals('/pl/scene/list');
        $I->dontSeeInRepository(Scene::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Scena "%s" została usunięta.', 'Tytuł nowej sceny'));
    }
}
