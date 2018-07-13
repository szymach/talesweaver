<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Controller\Chapter;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Chapter;
use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    private const LIST_URL = '/pl/chapter/list';
    private const TITLE_PL = 'Tytuł nowego rozdziału';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Chapter($id, self::TITLE_PL, null, $I->getUser()->getAuthor()));
        $I->seeInRepository(Chapter::class, ['id' => $id]);
        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Chapter::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Rozdział "%s" został usunięty.', self::TITLE_PL));
    }
}
