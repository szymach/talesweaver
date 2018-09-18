<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Controller\Scene;

use Talesweaver\Tests\FunctionalTester;

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
