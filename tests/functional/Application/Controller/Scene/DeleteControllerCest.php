<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Scene;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

class DeleteControllerCest
{
    private const LIST_URL = '/pl/scene/list';
    private const TITLE_PL = 'Tytuł nowej sceny';

    public function delete(FunctionalTester $I)
    {
        $I->loginAsUser();
        $id = Uuid::uuid4();
        $I->persistEntity(new Scene($id, new ShortText(self::TITLE_PL), null, $I->getAuthor()));
        $I->seeInRepository(Scene::class, ['id' => $id]);

        $I->amOnPage(self::LIST_URL);
        $I->canSeeNumberOfElements('tbody > tr', 1);
        $I->click('a[title="Usuń"]');

        $I->canSeeCurrentUrlEquals(self::LIST_URL);
        $I->dontSeeInRepository(Scene::class, ['id' => $id]);
        $I->canSeeAlert(sprintf('Scena "%s" została usunięta.', self::TITLE_PL));
    }
}
