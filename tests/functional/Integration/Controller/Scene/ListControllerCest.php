<?php

declare(strict_types=1);

namespace Integration\Tests\Controller\Scene;

use Integration\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/scene/list');
        $I->seeElement('h4');
        $I->see('Nie masz jeszcze żadnej nieprzypisanej do rozdziału sceny.');
    }
}
