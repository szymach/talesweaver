<?php

namespace Tests\AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use FunctionalTester;

class FormControllerCest
{
    const CREATE_URL = '/pl/scene/create';
    const EDIT_URL = '/pl/scene/edit/%s';

    const CREATE_FORM = 'form[name="create"]';
    const EDIT_FORM = 'form[name="edit"]';

    const TITLE_PL = 'Tytuł nowej sceny';
    const CONTENT_PL = 'Treść nowej sceny';
    const NEW_TITLE_PL = 'Zmieniony tytuł sceny';
    const NEW_CONTENT_PL = 'Zmieniona treść sceny';

    public function renderView(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage(self::CREATE_URL);
        $I->seeInTitle('Nowa scena');
        $I->seeElement(self::CREATE_FORM);
        $I->see('Tytuł', 'label[for="create_title"]');
        $I->see('Rozdział', 'label[for="create_chapter"]');
        $I->see('Wróć do listy', 'a');
    }

    public function submitForms(FunctionalTester $I)
    {
        $I->loginAsUser();
        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, ['create[title]' => self::TITLE_PL]);

        $scene = $I->grabEntityFromRepository(Scene::class, [
            'translations' => ['title' => self::TITLE_PL]
        ]);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $scene->getId()));
        $I->canSeeAlert(sprintf(
            'Pomyślnie dodano nową scenę o tytule "%s"',
            self::TITLE_PL
        ));
        $I->seeElement(self::EDIT_FORM);
        $I->seeInTitle(self::TITLE_PL);
        $I->see('Podgląd', 'a');
        $I->see('Wróć do listy', 'a');
        $I->seeElement('nav.side-menu');
        $I->see('Postacie', 'nav.side-menu .h4');
        $I->see('Przedmioty', 'nav.side-menu .h4');
        $I->see('Miejsca', 'nav.side-menu .h4');
        $I->see('Wydarzenia', 'nav.side-menu .h4');

        $I->submitForm(self::EDIT_FORM, [
            'edit[title]' => self::NEW_TITLE_PL,
            'edit[text]' => self::NEW_CONTENT_PL
        ]);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $scene->getId()));
        $I->seeInTitle(self::NEW_TITLE_PL);
        $I->canSeeAlert('Zapisano zmiany w scenie.');
    }
}
