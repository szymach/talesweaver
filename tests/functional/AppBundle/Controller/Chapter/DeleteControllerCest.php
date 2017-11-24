<?php

namespace Tests\AppBundle\Controller\Chapter;

use Domain\Chapter\Create\DTO;
use AppBundle\Entity\Chapter;
use FunctionalTester;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    const LIST_URL = '/pl/chapter/list';
    const TITLE_PL = 'Tytuł nowego rozdziału';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $dto = new DTO();
        $dto->setTitle(self::TITLE_PL);
        $I->persistEntity(new Chapter($id, $dto, $I->getUser()));
        $I->seeInRepository(Chapter::class, ['id' => $id]);
        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Chapter::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Rozdział "%s" został usunięty.', self::TITLE_PL));
    }
}
