<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Tests\FunctionalTester;

class FormControllerCest
{
    private const CREATE_URL = '/pl/chapter/create';
    private const EDIT_URL = '/pl/chapter/edit/%s';
    private const SCENE_EDIT_URL = '/pl/scene/edit/%s';

    private const CREATE_FORM = 'form[name="create"]';
    private const EDIT_FORM = 'form[name="edit"]';
    private const SCENE_CREATE_FORM = 'form[name="create"]';

    private const TITLE_PL = 'Tytuł nowego rozdziału';
    private const NEW_TITLE_PL = 'Zmieniony tytuł rozdziału';
    private const SCENE_TITLE_PL = 'Tytuł sceny przypisanej do rozdziału';

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

        $chapter = $I->grabEntityFromRepository(Chapter::class, [
            'translations' => ['title' => 'Tytuł nowego rozdziału']
        ]);
        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapter->getId()));
        $I->canSeeAlert(sprintf('Pomyślnie dodano nowy rozdział o tytule "%s"', 'Tytuł nowego rozdziału'));
        $I->seeElement('form[name="edit"]');
        $I->seeInTitle('Tytuł nowego rozdziału');
        $I->seeElement('form[name="create"]');

        $I->submitForm('form[name="edit"]', ['edit[title]' => 'Zmieniony tytuł rozdziału']);

        $I->seeCurrentUrlEquals(sprintf('/pl/chapter/edit/%s', $chapter->getId()));
        $I->seeInTitle('Zmieniony tytuł rozdziału');
        $I->canSeeAlert('Zapisano zmiany w rozdziale.');

        $I->submitForm('form[name="create"]', ['create[title]' => 'Tytuł sceny przypisanej do rozdziału']);
        $scene = $I->grabEntityFromRepository(Scene::class, [
            'translations' => ['title' => 'Tytuł sceny przypisanej do rozdziału']
        ]);
        $I->seeCurrentUrlEquals(sprintf('/pl/scene/edit/%s', $scene->getId()));
        $I->seeInTitle('Tytuł sceny przypisanej do rozdziału');
        $I->canSeeAlert(sprintf('Pomyślnie dodano nową scenę o tytule "%s"', 'Tytuł sceny przypisanej do rozdziału'));
    }
}
