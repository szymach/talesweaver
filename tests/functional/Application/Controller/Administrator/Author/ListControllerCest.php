<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Administrator\Author;

use Talesweaver\Tests\FunctionalTester;

final class ListControllerCest
{
    public function testListView(FunctionalTester $I): void
    {
        $I->loginAsAnAdministrator();
        $I->amOnPage('/administration/author/list');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/administration/author/list');

        $I->see('user1@example.com', 'td');
        $I->see('user2@example.com', 'td');
    }

    public function testAuthorNotAllowed(FunctionalTester $I): void
    {
        $I->loginAsUser('user1@example.com');

        $I->amOnPage('/administration/author/list');
        $I->seeCurrentUrlEquals('/administration/login');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->getAuthor('user1@example.com');
        $I->getAuthor('user2@example.com');
    }
}
