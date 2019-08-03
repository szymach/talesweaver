<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Tests\FunctionalTester;

final class PositionControllerCest
{
    public function testAction(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Book $book */
        $book = $I->haveCreatedABook('Książka');

        /** @var Chapter $chapter1 */
        $chapter1 = $I->haveCreatedAChapter('Rozdział 1', $book);
        $I->assertEquals(0, $chapter1->getPosition());

        /** @var Chapter $chapter2 */
        $chapter2 = $I->haveCreatedAChapter('Rozdział 2', $book);
        $I->assertEquals(0, $chapter2->getPosition());

        $I->amOnPage("/pl/chapter/edit/{$book->getId()->toString()}");
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');

        $I->sendPOST('/pl/chapter/position/multiple', [
            ['id' => $chapter1->getId()->toString(), 'position' => 1],
            ['id' => $chapter2->getId()->toString(), 'position' => 0]
        ]);

        $I->seeResponseCodeIs(200);

        $chapter1 = $I->grabChapterByTitle('Rozdział 1');
        $I->assertEquals(1, $chapter1->getPosition());
        $chapter2 = $I->grabChapterByTitle('Rozdział 2');
        $I->assertEquals(0, $chapter2->getPosition());
    }
}
