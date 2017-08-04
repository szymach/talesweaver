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
        $I->seeInTitle('Nowy rozdział');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, ['create[title]' => self::TITLE_PL]);

        $chapter = $I->grabEntityFromRepository(Chapter::class, [
            'translations' => ['title' => self::TITLE_PL]
        ]);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $chapter->getId()));
        $I->seeElement(self::EDIT_FORM);
        $I->seeInTitle(self::TITLE_PL);
        $I->seeElement(self::SCENE_CREATE_FORM);

        $I->submitForm(self::EDIT_FORM, ['edit[title]' => self::NEW_TITLE_PL]);

        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $chapter->getId()));
        $I->seeInTitle(self::NEW_TITLE_PL);

        $I->submitForm(self::SCENE_CREATE_FORM, ['create[title]' => self::SCENE_TITLE_PL]);
        $scene = $I->grabEntityFromRepository(Scene::class);
        $I->seeCurrentUrlEquals(sprintf(self::SCENE_EDIT_URL, $scene->getId()));
        $I->seeInTitle(self::SCENE_TITLE_PL);
    }
}
