<?php

declare(strict_types=1);

namespace App\Tests\Controller\Chapter;

use App\Tests\FunctionalTester;
use Domain\Entity\Chapter;
use Ramsey\Uuid\Uuid;

class DeleteControllerCest
{
    private const LIST_URL = '/pl/chapter/list';
    private const TITLE_PL = 'Tytuł nowego rozdziału';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Chapter($id, self::TITLE_PL, null, $I->getUser()));
        $I->seeInRepository(Chapter::class, ['id' => $id]);
        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Chapter::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Rozdział "%s" został usunięty.', self::TITLE_PL));
    }
}
