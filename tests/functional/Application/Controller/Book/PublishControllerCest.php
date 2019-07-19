<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Book;

use Talesweaver\Domain\Book;
use Talesweaver\Tests\FunctionalTester;

final class PublishControllerCest
{
    public function testBookPublication(FunctionalTester $I): void
    {
        /** @var Book $book */
        $book = $I->haveCreatedABook('Książka');
        $bookId = $book->getId()->toString();

        $I->amOnPage("/pl/book/edit/{$bookId}");
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/book/publish/{$bookId}");
        $I->seeResponseCodeIs(200);

        $I->sendPOST("/pl/book/publish/{$bookId}", [
            'publish' => [
                'title' => 'Publikacja',
                'visible' => 1,
                '_token' => $I->fetchTokenFromAjaxResponse('#publish__token')
            ]
        ]);
        $I->seeResponseCodeIs(200);

        /** @var Book $book */
        $book = $I->grabBookByTitle('Książka');
        $currentPublication = $book->getCurrentPublication('pl');
        $I->assertNotNull($currentPublication);
        $I->assertTrue($currentPublication->isVisible());
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();
    }
}
