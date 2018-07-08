<?php

declare(strict_types=1);

namespace Integration\Tests\Controller\Chapter;

use Integration\Tests\FunctionalTester;

class ListControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/list');
        $I->seeElement('h4');
        $I->see('Nie masz jeszcze żadnego nieprzypisanego do książki rozdziału.');
    }
}
