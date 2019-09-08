<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller;

use Talesweaver\Tests\FunctionalTester;

final class StaticPagesControllerCest
{
    public function testFirstStepsPage(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $I->amOnPage('/en/page/first-steps');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/en/page/first-steps');
    }

    public function testAboutPage(FunctionalTester $I): void
    {
        $I->amOnPage('/en/page/about');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/en/page/about');

        $I->loginAsUser();
        $I->amOnPage('/en/page/about');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/en/page/about');
    }
}
