<?php

declare(strict_types=1);

namespace Integration\Tests\Controller\Chapter;

use Integration\Tests\FunctionalTester;
use Domain\Chapter;
use Domain\Scene;

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
        $I->amOnPage(self::CREATE_URL);
        $I->seeInTitle('Nowy rozdział');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, ['create[title]' => self::TITLE_PL]);

        $chapter = $I->grabEntityFromRepository(Chapter::class, [
            'translations' => ['title' => self::TITLE_PL]
        ]);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $chapter->getId()));
        $I->canSeeAlert(sprintf(
            'Pomyślnie dodano nowy rozdział o tytule "%s"',
            self::TITLE_PL
        ));
        $I->seeElement(self::EDIT_FORM);
        $I->seeInTitle(self::TITLE_PL);
        $I->seeElement(self::SCENE_CREATE_FORM);

        $I->submitForm(self::EDIT_FORM, ['edit[title]' => self::NEW_TITLE_PL]);

        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $chapter->getId()));
        $I->seeInTitle(self::NEW_TITLE_PL);
        $I->canSeeAlert('Zapisano zmiany w rozdziale.');

        $I->submitForm(self::SCENE_CREATE_FORM, ['create[title]' => self::SCENE_TITLE_PL]);
        $scene = $I->grabEntityFromRepository(Scene::class, [
            'translations' => ['title' => self::SCENE_TITLE_PL]
        ]);
        $I->seeCurrentUrlEquals(sprintf(self::SCENE_EDIT_URL, $scene->getId()));
        $I->seeInTitle(self::SCENE_TITLE_PL);
        $I->canSeeAlert(sprintf(
            'Pomyślnie dodano nową scenę o tytule "%s"',
            self::SCENE_TITLE_PL
        ));
    }
}
