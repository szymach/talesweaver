<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Chapter;
use Talesweaver\Tests\FunctionalTester;

final class DeleteControllerCest
{
    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();

        /** @var Chapter $chapter */
        $chapter = $I->haveCreatedAChapter('Tytuł nowego rozdziału');

        $I->amOnPage('/pl/chapter/list');
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals('/pl/chapter/list');

        $I->seeChapterHasBeenRemoved($chapter->getId());
        $I->canSeeAlert('Rozdział "Tytuł nowego rozdziału" został usunięty.');
    }
}
