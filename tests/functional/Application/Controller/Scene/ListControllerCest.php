<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Tests\FunctionalTester;

class ListControllerCest
{
    public function testSceneList(FunctionalTester $I)
    {
        $I->loginAsUser();
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
}
