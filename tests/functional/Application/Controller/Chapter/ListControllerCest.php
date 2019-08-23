<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Tests\FunctionalTester;

final class ListControllerCest
{
    public function testChapterList(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/chapter/list');
        $I->see('Nie znaleziono żadnego rozdziału.', 'h4');
        $I->see('Dodaj nowy!', 'a[href="/pl/chapter/create"]');
        $I->cantSeeElement('table');

        $I->haveCreatedAChapter('Rozdział');
        $I->amOnPage('/pl/chapter/list');
        $I->see('Rozdział', 'td');
        $I->seeElement('a[title="Edycja"]');
        $I->seeElement('a[title="Usuń"]');
        $I->seeElement('a[title="Nowy"]');

        $I->click('a[title="Edycja"]');
        $I->canSeeInTitle('Rozdział - edycja');
    }

    public function testFilteringChapterList(FunctionalTester $I): void
    {
        $book = $I->haveCreatedABook('Książka 1');
        $I->haveCreatedAChapter('Rozdział książki 1', $book);
        $I->haveCreatedAChapter('Rozdział książki 2', $I->haveCreatedABook('Książka 2'));

        $I->amOnPage("/pl/chapter/list");
        $I->see('Rozdział książki 1', 'td');
        $I->see('Rozdział książki 2', 'td');

        $I->selectOption('select[name="filters[book]"]', $book->getId()->toString());
        $I->click('Filtruj');
        $I->see('Rozdział książki 1', 'td');
        $I->cantSee('Rozdział książki 2', 'td');

        $I->click('Wyczyść');
        $I->see('Rozdział książki 1', 'td');
        $I->see('Rozdział książki 2', 'td');

        $I->click('Sortuj malejąco', 'th:first-child a');
        $I->see('Rozdział książki 2', 'tr:first-child td');

        $I->click('Sortuj rosnąco', 'th:first-child a');
        $I->see('Rozdział książki 1', 'tr:first-child td');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();
    }
}
