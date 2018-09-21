<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class FormControllerCest
{
    private const CREATE_URL = '/pl/scene/create';
    private const EDIT_URL = '/pl/scene/edit/%s';

    private const CREATE_FORM = 'form[name="create"]';
    private const EDIT_FORM = 'form[name="edit"]';
    private const NEXT_FORM = 'nav form[name="create"]';

    private const TITLE_PL = 'Tytuł nowej sceny';
    private const CONTENT_PL = 'Treść nowej sceny';
    private const NEW_TITLE_PL = 'Zmieniony tytuł sceny';
    private const NEW_CONTENT_PL = 'Zmieniona treść sceny';

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

        $scene = $I->grabEntityFromRepository(Scene::class, ['translations' => ['title' => self::TITLE_PL]]);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $scene->getId()));
        $I->canSeeAlert(sprintf('Pomyślnie dodano nową scenę o tytule "%s"', self::TITLE_PL));
        $I->seeElement(self::EDIT_FORM);
        $I->seeInTitle(self::TITLE_PL);
        $I->see('Podgląd', 'a');
        $I->see('PDF', 'a');
        $I->see('Wróć do listy', 'a');
        $I->seeElement('nav.side-menu');
        $I->see('Postacie', 'span');
        $I->see('Przedmioty', 'span');
        $I->see('Miejsca', 'span');
        $I->see('Wydarzenia', 'span');

        $I->submitForm(self::EDIT_FORM, [
            'edit[title]' => self::NEW_TITLE_PL,
            'edit[text]' => self::NEW_CONTENT_PL
        ]);
        $I->seeCurrentUrlEquals(sprintf(self::EDIT_URL, $scene->getId()));
        $I->seeInTitle(self::NEW_TITLE_PL);
        $I->canSeeAlert('Zapisano zmiany w scenie.');
    }

    public function nextSceneForm(FunctionalTester $I)
    {
        $author = $I->getAuthor();
        $chapter = new Chapter(Uuid::uuid4(), new ShortText('Rozdział'), null, $author);
        $I->persistEntity($chapter);
        $id = Uuid::uuid4();
        $I->persistEntity(new Scene($id, new ShortText(self::TITLE_PL), $chapter, $author));

        $I->cantSeeInRepository(Scene::class, ['translations' => ['title' => self::NEW_TITLE_PL]]);
        $I->loginAsUser();
        $I->amOnPage(sprintf(self::EDIT_URL, $id->toString()));
        $I->seeElement(self::NEXT_FORM);
        $I->seeElement(self::EDIT_FORM);
        $I->submitForm(self::NEXT_FORM, ['create[title]' => self::NEW_TITLE_PL]);
        $I->seeCurrentUrlMatches(
            '/\/pl\/scene\/edit\/[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}/'
        );
        $I->canSeeInRepository(Scene::class, ['translations' => ['title' => self::NEW_TITLE_PL]]);
    }
}
