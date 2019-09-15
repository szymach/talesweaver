<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Administrator\Author;

use Talesweaver\Domain\Author;
use Talesweaver\Tests\FunctionalTester;

final class ResendActivatonCodeControllerCest
{
    public function testResendingActivationCode(FunctionalTester $I): void
    {
        $I->amOnPage('/administration/author/list');
        $I->stopFollowingRedirects();
        $I->click('Wyślij ponownie kod aktywacyjny');
        $I->seeResponseCodeIs(302);
        $I->startFollowingRedirects();

        /** @var Author $author */
        $author = $I->getAuthor('user@example.com', 'password');
        $I->assertNotNull($author->getActivationToken());
        $I->seeAnEmailHasBeenSent('Bajkopisarz - rejestracja', 'user@example.com');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsAnAdministrator();
        $I->getAuthor('user@example.com', 'password', false);
        $I->haveClearedEmailSpool();
    }
}
