<?php

namespace Tests\AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;
use FunctionalTester;

class FormControllerCest
{
    const CREATE_URL = '/pl/chapter/create';
    const EDIT_URL = '/pl/chapter/edit/%s';
    const SCENE_EDIT_URL = '/pl/scene/edit/%s';

    const CREATE_FORM = 'form[name="create"]';
    const EDIT_FORM = 'form[name="edit"]';
    const SCENE_CREATE_FORM = 'form[name="create"]';

    const TITLE_PL = 'Tytuł nowego rozdziału';
    const NEW_TITLE_PL = 'Zmieniony tytuł rozdziału';
    const SCENE_TITLE_PL = 'Tytuł sceny przypisanego do rozdziału';

    public function renderView(FunctionalTester $I)
    {
        $I->amOnPage(self::CREATE_URL);
        $I->see('Nowy rozdział', 'h1');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, ['create[title]' => self::TITLE_PL]);

        $chapter = $I->grabEntityFromRepository(Chapter::class);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $chapter->getId()));
        $I->seeElement(self::EDIT_FORM);
        $I->canSee(self::TITLE_PL, 'h1');
        $I->canSee('Sceny', 'h2');
        $I->seeElement(self::SCENE_CREATE_FORM);
        $I->seeElement('table');

        $I->submitForm(self::EDIT_FORM, ['edit[title]' => self::NEW_TITLE_PL]);

        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $chapter->getId()));
        $I->see(self::NEW_TITLE_PL, 'h1');

        $I->submitForm(self::SCENE_CREATE_FORM, ['create[title]' => self::SCENE_TITLE_PL]);
        $scene = $I->grabEntityFromRepository(Scene::class);
        $I->seeCurrentUrlEquals(sprintf(self::SCENE_EDIT_URL, $scene->getId()));
        $I->canSee(self::SCENE_TITLE_PL, 'h1');
    }
}
