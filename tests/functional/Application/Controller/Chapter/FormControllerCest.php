<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Tests\FunctionalTester;

final class FormControllerCest
{
    /**
     * @var string
     */
    private $bookId;

    public function renderView(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/chapter/create');
        $I->seeInTitle('Nowy rozdział');
        $I->seeElement('form[name="create"]');
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Wstęp', 'label[for="create_preface"]');
        $I->see('Książka', 'label[for="create_book"]');
    }

    public function submitForms(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/chapter/create');
        $I->submitForm('form[name="create"]', [
            'create[title]' => 'Tytuł nowego rozdziału',
            'create[preface]' => 'Wstęp nowego rozdziału',
            'create[book]' => $this->bookId
        ]);

        /** @var Chapter $chapter */
        $chapter = $I->grabChapterByTitle('Tytuł nowego rozdziału');
        $chapterId = $chapter->getId()->toString();

        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapterId));
        $I->canSeeAlert('Pomyślnie dodano nowy rozdział o tytule "Tytuł nowego rozdziału"');
        $I->seeElement('form[name="edit"]');
        $I->seeInTitle('Tytuł nowego rozdziału');

        $I->submitForm(
            'form[name="edit"]',
            ['edit[title]' => 'Zmieniony tytuł rozdziału', 'edit[preface]' => 'Zmieniony wstęp rozdziału']
        );

        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapterId));
        $I->seeInTitle('Zmieniony tytuł rozdziału');
        $I->canSeeAlert('Zapisano zmiany w rozdziale.');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Book $book */
        $book = $I->haveCreatedABook('Książka');
        $this->bookId = $book->getId()->toString();
    }
}
