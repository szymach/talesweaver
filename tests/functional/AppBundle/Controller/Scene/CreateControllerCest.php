<?php

namespace Tests\AppBundle\Controller\Scene;

use FunctionalTester;

class CreateControllerCest
{
    const URL = '/pl/scene/create';
    const CREATE_FORM = 'form[name="create"]';
    const TITLE_PL = 'Tytuł nowej sceny';

    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage(self::URL);
        $I->see('Nowa scena', 'h1');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForm(FunctionalTester $I)
    {
        $I->amOnPage(self::URL);
        $I->submitForm(self::CREATE_FORM, ['create[title]' => self::TITLE_PL]);

        $I->seeElement('form[name="edit"]');
        $I->canSee(self::TITLE_PL, 'h1');
    }
}
