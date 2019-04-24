<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Tests\FunctionalTester;

class ListControllerCest
{
    public function testChapterList(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/list');
        $I->see('Nie masz jeszcze żadnego rozdziału.', 'h4');
        $I->see('Dodaj nowy!', 'a[href="/pl/chapter/create"]');
        $I->cantSeeElement('table');

        $I->haveCreatedAChapter('Rozdział');
        $I->amOnPage('/pl/chapter/list');
        $I->see('Rozdział', 'td');
        $I->seeElement('a[title="Edycja"]');
        $I->seeElement('a[title="Usuń"]');
        $I->seeElement('a[title="Nowy"]');

        $I->click('a[title="Edycja"]');
        $I->canSeeInTitle('Rozdział - edycja');
    }
}
