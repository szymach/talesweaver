<?php

declare(strict_types=1);

namespace Integration\Tests\Controller\Scene;

use Integration\Tests\FunctionalTester;
use Domain\Scene;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    private const LIST_URL = '/pl/scene/list';
    private const TITLE_PL = 'Tytuł nowej sceny';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Scene($id, self::TITLE_PL, null, $I->getUser()));
        $I->seeInRepository(Scene::class, ['id' => $id]);

        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');

        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Scene::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Scena "%s" została usunięta.', self::TITLE_PL));
    }
}
