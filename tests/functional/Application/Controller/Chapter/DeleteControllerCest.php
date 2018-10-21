<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $chapterId = $I->haveCreatedAChapter('Tytuł nowego rozdziału')->getId();
        $I->amOnPage('/pl/chapter/list');
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');
        $I->canSeeCurrentUrlEquals('/pl/chapter/list');
        $I->seeChapterHasBeenRemoved($chapterId);
        $I->canSeeAlert('Rozdział "Tytuł nowego rozdziału" został usunięty.');
    }
}
