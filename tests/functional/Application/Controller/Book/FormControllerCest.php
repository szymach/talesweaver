<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Tests\FunctionalTester;

class FormControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/book/create');
        $I->seeInTitle('Nowa książka');
        $I->seeElement('form[name="create"]');
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Opis', 'label[for="create_description"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/book/create');
        $I->submitForm('form[name="create"]', [
            'create[title]' => 'Tytuł nowej książki',
            'create[description]' => 'Zmieniony opis książki'
        ]);

        $bookId = $I->grabBookByTitle('Tytuł nowej książki')->getId()->toString();
        $I->seeElement('form[name="edit"]');
        $I->seeCurrentUrlEquals(sprintf('/pl/book/edit/%s', $bookId));

        $I->seeElement('form[name="edit"]');
        $I->canSeeAlert('Pomyślnie dodano nową książkę o tytule "Tytuł nowej książki"');
        $I->seeInTitle('Tytuł nowej książki');
        $I->submitForm('form[name="edit"]', [
            'edit[title]' => 'Zmieniony tytuł książki',
            'edit[description]' => 'Zmieniony opis książki'
        ]);

        $I->seeCurrentUrlEquals(sprintf('/pl/book/edit/%s', $bookId));
        $I->seeInTitle('Zmieniony tytuł książki');
        $I->canSeeAlert('Zapisano zmiany w książce.');
    }
}
