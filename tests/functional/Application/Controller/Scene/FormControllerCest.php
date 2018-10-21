<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Tests\FunctionalTester;

class FormControllerCest
{
    public function renderView(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/scene/create');
        $I->seeInTitle('Nowa scena');
        $I->seeElement('form[name="create"]');
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Rozdział', 'label[for="create_chapter"]');
        $I->see('Wróć do listy', 'a');
    }

    public function submitForms(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $I->amOnPage('/pl/scene/create');
        $I->submitForm('form[name="create"]', ['create[title]' => 'Tytuł nowej sceny']);

        $sceneId = $I->grabSceneByTitle('Tytuł nowej sceny')->getId()->toString();
        $I->seeCurrentUrlEquals("/pl/scene/edit/{$sceneId}");
        $I->canSeeAlert('Pomyślnie dodano nową scenę o tytule "Tytuł nowej sceny"');
        $I->seeElement('form[name="edit"]');
        $I->seeInTitle('Tytuł nowej sceny');
        $I->see('Podgląd', 'a');
        $I->see('PDF', 'a');
        $I->see('Wróć do listy', 'a');
        $I->seeElement('nav.side-menu');
        $I->see('Postacie', 'span');
        $I->see('Przedmioty', 'span');
        $I->see('Miejsca', 'span');
        $I->see('Wydarzenia', 'span');

        $I->submitForm('form[name="edit"]', [
            'edit[title]' => 'Zmieniony tytuł sceny',
            'edit[text]' => 'Treść sceny'
        ]);
        $I->seeCurrentUrlEquals("/pl/scene/edit/{$sceneId}");
        $I->seeInTitle('Zmieniony tytuł sceny');
        $I->canSeeAlert('Zapisano zmiany w scenie.');
    }

    public function nextSceneForm(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $sceneId = $I->haveCreatedAScene(
            'Tytuł nowej sceny',
            $I->haveCreatedAChapter('Rozdział')
        )->getId()->toString();

        $I->amOnPage("/pl/scene/edit/{$sceneId}");
        $I->seeElement('nav form[name="create"]');
        $I->seeElement('form[name="edit"]');
        $I->submitForm('nav form[name="create"]', ['create[title]' => 'Zmieniony tytuł sceny']);
        $I->seeCurrentUrlMatches(
            '/\/pl\/scene\/edit\/[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}/'
        );
        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Zmieniony tytuł sceny - edycja');
    }
}
