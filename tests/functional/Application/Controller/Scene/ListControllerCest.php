<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Tests\FunctionalTester;

final class ListControllerCest
{
    public function testSceneList(FunctionalTester $I)
    {
        $I->amOnPage('/pl/scene/list');
        $I->see('Nie masz jeszcze żadnej sceny.', 'h4');
        $I->see('Dodaj nową!', 'a[href="/pl/scene/create"]');
        $I->cantSeeElement('table');

        $I->haveCreatedAScene('Scena');
        $I->amOnPage('/pl/scene/list');
        $I->see('Scena', 'td');
        $I->seeElement('a[title="Edycja"]');
        $I->seeElement('a[title="Usuń"]');
        $I->seeElement('a[title="Nowa"]');

        $I->click('a[title="Edycja"]');
        $I->canSeeInTitle('Scena - edycja');
    }

    public function testFilteringSceneList(FunctionalTester $I): void
    {
        $chapter = $I->haveCreatedAChapter('Rozdział 1');
        $I->haveCreatedAScene('Scena rozdziału 1', $chapter);
        $I->haveCreatedAScene('Scena rozdziału 2', $I->haveCreatedAChapter('Rozdział 2'));

        $I->amOnPage('/pl/scene/list');
        $I->see('Scena rozdziału 1', 'td');
        $I->see('Scena rozdziału 2', 'td');

        $I->selectOption('select[name="chapter"]', $chapter->getId()->toString());
        $I->click('Filtruj');
        $I->see('Scena rozdziału 1', 'td');
        $I->cantSee('Scena rozdziału 2', 'td');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();
    }
}
