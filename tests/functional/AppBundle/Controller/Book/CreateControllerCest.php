<?php

namespace Tests\AppBundle\Controller\Book;

use FunctionalTester;

class CreateControllerCest
{
    const URL = '/pl/book/create';
    const CREATE_FORM = 'form[name="create"]';
    const TITLE_PL = 'Tytuł nowej książki';
    const DESCRIPTION_PL = 'Opis nowej książki';

    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage(self::URL);
        $I->see('Nowa książka', 'h1');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Opis (publiczny)', 'label[for="create_description"]');
    }

    public function submitForm(FunctionalTester $I)
    {
        $I->amOnPage(self::URL);
        $I->submitForm(self::CREATE_FORM, [
            'create[title]' => self::TITLE_PL,
            'create[description]' => self::DESCRIPTION_PL
        ]);

        $I->seeElement('form[name="edit"]');
        $I->canSee(self::TITLE_PL, 'h1');
    }
}