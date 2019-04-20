<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Domain\Scene;
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
        $I->seeElement('a[title="Przejdź do strony z podglądem"]');
        $I->seeElement('a[title="Pobierz w formacie PDF"]');
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

    public function ajaxEditSubmit(FunctionalTester $I): void
    {
        $I->loginAsUser();
        /* @var $scene Scene */
        $sceneId = $I->haveCreatedAScene('Scena')->getId()->toString();
        $I->amOnPage("/pl/scene/edit/{$sceneId}");
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendPOST("/pl/scene/edit/{$sceneId}", [
            'edit' => [
                'title' => 'Scena edytowana',
                'text' => 'Opis sceny',
                'chapter' => null,
                '_token' => $I->grabValueFrom('#edit__token')
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([]);

        $updatedScene = $I->grabSceneByTitle('Scena edytowana');
        $I->assertEquals('Scena edytowana', $updatedScene->getTitle());
        $I->assertEquals('Opis sceny', $updatedScene->getText());
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
