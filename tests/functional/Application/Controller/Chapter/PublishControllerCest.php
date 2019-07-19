<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Chapter;

use Talesweaver\Domain\Chapter;
use Talesweaver\Tests\FunctionalTester;

final class PublishControllerCest
{
    public function testChapterPublication(FunctionalTester $I): void
    {
        /** @var Chapter $chapter */
        $chapter = $I->haveCreatedAChapter('Rozdział');
        $chapterId = $chapter->getId()->toString();

        $I->amOnPage("/pl/chapter/edit/{$chapterId}");
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET("/pl/chapter/publish/{$chapterId}");
        $I->seeResponseCodeIs(200);

        $I->sendPOST("/pl/chapter/publish/{$chapterId}", [
            'publish' => [
                'title' => 'Publikacja',
                'visible' => 1,
                '_token' => $I->fetchTokenFromAjaxResponse('#publish__token')
            ]
        ]);
        $I->seeResponseCodeIs(200);

        /** @var Chapter $chapter */
        $chapter = $I->grabChapterByTitle('Rozdział');
        $currentPublication = $chapter->getCurrentPublication('pl');
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
