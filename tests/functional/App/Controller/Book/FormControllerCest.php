<?php

declare(strict_types=1);

namespace App\Tests\Controller\Book;

use App\Tests\FunctionalTester;
use Domain\Entity\Book;

class FormControllerCest
{
    private const CREATE_URL = '/pl/book/create';
    private const EDIT_URL = '/pl/book/edit/%s';

    private const CREATE_FORM = 'form[name="create"]';
    private const EDIT_FORM = 'form[name="edit"]';

    private const TITLE_PL = 'Tytuł nowej książki';
    private const DESCRIPTION_PL = 'Opis nowej książki';
    private const NEW_TITLE_PL = 'Zmieniony tytuł książki';
    private const NEW_DESCRIPTION_PL = 'Zmieniony opis książki';

    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage(self::CREATE_URL);
        $I->seeInTitle('Nowa książka');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Opis', 'label[for="create_description"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->loginAsUser();
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
        $I->canSeeAlert(sprintf(
            'Pomyślnie dodano nową książkę o tytule "%s"',
            self::TITLE_PL
        ));
        $I->seeInTitle(self::TITLE_PL);
        $I->submitForm(self::EDIT_FORM, [
            'edit[title]' => self::NEW_TITLE_PL,
            'edit[description]' => self::NEW_DESCRIPTION_PL
        ]);

        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $book->getId()));
        $I->seeInTitle(self::NEW_TITLE_PL);
        $I->canSeeAlert('Zapisano zmiany w książce.');
    }
}
