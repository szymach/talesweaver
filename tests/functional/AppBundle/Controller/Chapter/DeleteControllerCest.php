<?php

namespace Tests\AppBundle\Controller\Chapter;

use AppBundle\Chapter\Create\DTO;
use AppBundle\Entity\Chapter;
use FunctionalTester;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    const LIST_URL = '/pl/chapter/list';
    const TITLE_PL = 'Tytuł nowego rozdziału';

    public function delete(FunctionalTester $I)
    {
        $I->amOnPage(self::LIST_URL);
        $id = Uuid::uuid4();
        $dto = new DTO();
        $dto->setTitle(self::TITLE_PL);
        $I->persistEntity(new Chapter($id, $dto));
        $I->seeInRepository(Chapter::class, ['id' => $id]);
        $I->amOnPage(self::LIST_URL);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Chapter::class, ['id' => $id]);
    }
}
