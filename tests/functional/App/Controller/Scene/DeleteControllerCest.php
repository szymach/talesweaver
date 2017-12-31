<?php

namespace App\Tests\Controller\Scene;

use Domain\Scene\Create\DTO;
use App\Entity\Scene;
use App\Tests\FunctionalTester;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    const LIST_URL = '/pl/scene/list';
    const TITLE_PL = 'Tytuł nowej sceny';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $dto = new DTO();
        $dto->setTitle(self::TITLE_PL);
        $I->persistEntity(new Scene($id, $dto, $I->getUser()));
        $I->seeInRepository(Scene::class, ['id' => $id]);

        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');

        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Scene::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Scena "%s" została usunięta.', self::TITLE_PL));
    }
}
