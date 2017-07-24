<?php

namespace Tests\AppBundle\Controller\Book;

use AppBundle\Entity\Book;
use FunctionalTester;

class FormControllerCest
{
    const CREATE_URL = '/pl/book/create';
    const EDIT_URL = '/pl/book/edit/%s';

    const CREATE_FORM = 'form[name="create"]';
    const EDIT_FORM = 'form[name="edit"]';

    const TITLE_PL = 'Tytuł nowej książki';
    const DESCRIPTION_PL = 'Opis nowej książki';
    const NEW_TITLE_PL = 'Zmieniony tytuł książki';
    const NEW_DESCRIPTION_PL = 'Zmieniony opis książki';

    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage(self::CREATE_URL);
        $I->see('Nowa książka', 'h1');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Opis (publiczny)', 'label[for="create_description"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, [
            'create[title]' => self::TITLE_PL,
            'create[description]' => self::DESCRIPTION_PL
        ]);

        $book = $I->grabEntityFromRepository(Book::class, [
            'translations' => ['title' => self::TITLE_PL]
        ]);
        $I->seeElement(self::EDIT_FORM);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $book->getId()));

        $I->seeElement(self::EDIT_FORM);
        $I->see(self::TITLE_PL, 'h1');
        $I->submitForm(self::EDIT_FORM, [
            'edit[title]' => self::NEW_TITLE_PL,
            'edit[description]' => self::NEW_DESCRIPTION_PL
        ]);

        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $book->getId()));
        $I->see(self::NEW_TITLE_PL, 'h1');
    }
}
