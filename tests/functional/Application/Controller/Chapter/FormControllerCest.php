<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Tests\FunctionalTester;

final class FormControllerCest
{
    public function renderView(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/chapter/create');
        $I->seeInTitle('Nowy rozdział');
        $I->seeElement('form[name="create"]');
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForms(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/chapter/create');
        $I->submitForm('form[name="create"]', ['create[title]' => 'Tytuł nowego rozdziału']);

        $chapterId = $I->grabChapterByTitle('Tytuł nowego rozdziału')->getId()->toString();
        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapterId));
        $I->canSeeAlert('Pomyślnie dodano nowy rozdział o tytule "Tytuł nowego rozdziału"');
        $I->seeElement('form[name="edit"]');
        $I->seeInTitle('Tytuł nowego rozdziału');

        $I->submitForm('form[name="edit"]', ['edit[title]' => 'Zmieniony tytuł rozdziału']);

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
    }
}
