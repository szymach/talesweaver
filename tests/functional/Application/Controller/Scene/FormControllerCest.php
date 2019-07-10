<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

final class FormControllerCest
{
    public function renderView(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/scene/create');
        $I->seeInTitle('Nowa scena');
        $I->seeElement('form[name="create"]');
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Rozdział', 'label[for="create_chapter"]');
        $I->see('Wróć do listy', 'a');
    }

    public function submitForms(FunctionalTester $I): void
    {
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
        $I->see('Postacie', 'a');
        $I->see('Przedmioty', 'a');
        $I->see('Miejsca', 'a');
        $I->see('Wydarzenia', 'a');

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

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTest $I): void
    {
        $I->loginAsUser();
    }
}
