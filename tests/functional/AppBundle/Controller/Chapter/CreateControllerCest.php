<?php

namespace Tests\AppBundle\Controller\Chapter;

use FunctionalTester;

class CreateControllerCest
{
    const URL = '/pl/chapter/create';
    const CREATE_FORM = 'form[name="create"]';
    const TITLE_PL = 'Tytuł nowego rozdziału';

    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage(self::URL);
        $I->see('Nowy rozdział', 'h1');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForm(FunctionalTester $I)
    {
        $I->amOnPage(self::URL);
        $I->submitForm(self::CREATE_FORM, ['create[title]' => self::TITLE_PL]);

        $I->seeElement('form[name="edit"]');
        $I->canSee(self::TITLE_PL, 'h1');
        $I->canSee('Sceny', 'h2');
        $I->seeElement('form[name="create"]');
        $I->seeElement('table');
    }
}
