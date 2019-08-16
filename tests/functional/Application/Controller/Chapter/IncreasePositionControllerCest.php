<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Tests\FunctionalTester;

final class IncreasePositionControllerCest
{
    public function testAction(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Book $book */
        $book = $I->haveCreatedABook('Książka');

        /** @var Chapter $chapter1 */
        $chapter1 = $I->haveCreatedAChapter('Rozdział 1', $book);

        /** @var Chapter $chapter2 */
        $chapter2 = $I->haveCreatedAChapter('Rozdział 2', $book);

        $I->refreshEntities([$chapter1, $chapter2]);

        $I->assertEquals(1, $chapter1->getPosition());
        $I->assertEquals(0, $chapter2->getPosition());

        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/chapter/position/single/increase/{$chapter2->getId()->toString()}");

        $I->seeResponseCodeIs(200);

        $I->refreshEntities([$chapter1, $chapter2]);

        $I->assertEquals(0, $chapter1->getPosition());
        $I->assertEquals(1, $chapter2->getPosition());
    }
}
