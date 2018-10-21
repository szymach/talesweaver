<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Tests\FunctionalTester;

class FormControllerCest
{
    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/create');
        $I->seeInTitle('Nowy rozdział');
        $I->seeElement('form[name="create"]');
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/chapter/create');
        $I->submitForm('form[name="create"]', ['create[title]' => 'Tytuł nowego rozdziału']);

        $chapterId = $I->grabChapterByTitle('Tytuł nowego rozdziału')->getId()->toString();
        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapterId));
        $I->canSeeAlert(sprintf('Pomyślnie dodano nowy rozdział o tytule "%s"', 'Tytuł nowego rozdziału'));
        $I->seeElement('form[name="edit"]');
        $I->seeInTitle('Tytuł nowego rozdziału');
        $I->seeElement('form[name="create"]');

        $I->submitForm('form[name="edit"]', ['edit[title]' => 'Zmieniony tytuł rozdziału']);

        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapterId));
        $I->seeInTitle('Zmieniony tytuł rozdziału');
        $I->canSeeAlert('Zapisano zmiany w rozdziale.');

        $I->submitForm('form[name="create"]', ['create[title]' => 'Tytuł sceny przypisanej do rozdziału']);
        $sceneId = $I->grabSceneByTitle('Tytuł sceny przypisanej do rozdziału')->getId()->toString();
        $I->seeCurrentUrlEquals(sprintf('/pl/scene/edit/%s', $sceneId));
        $I->seeInTitle('Tytuł sceny przypisanej do rozdziału');
        $I->canSeeAlert(sprintf('Pomyślnie dodano nową scenę o tytule "%s"', 'Tytuł sceny przypisanej do rozdziału'));
    }
}
