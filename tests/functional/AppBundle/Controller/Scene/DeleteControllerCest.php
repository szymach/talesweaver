<?php

namespace Tests\AppBundle\Controller\Scene;

use AppBundle\Scene\Create\DTO;
use AppBundle\Entity\Scene;
use FunctionalTester;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    const LIST_URL = '/pl/scene/list';
    const TITLE_PL = 'Tytuł nowej sceny';

    public function delete(FunctionalTester $I)
    {
        $I->amOnPage(self::LIST_URL);

        $id = Uuid::uuid4();
        $dto = new DTO();
        $dto->setTitle(self::TITLE_PL);
        $I->persistEntity(new Scene($id, $dto));
        $I->seeInRepository(Scene::class, ['id' => $id]);

        $I->amOnPage(self::LIST_URL);
        $I->click('a[title="Usuń"]');

        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Scene::class, ['id' => $id]);
    }
}
