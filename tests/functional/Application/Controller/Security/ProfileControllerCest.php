<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

final class ProfileControllerCest
{
    public function testProfilePageResponse(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/profile');
        $I->seeCurrentUrlEquals('/pl/profile');
        $I->seeResponseCodeIs(200);

        $I->canSee('Imię', 'label');
        $I->canSee('Nazwisko', 'label');
    }

    public function testProfileSubmission(FunctionalTester $I): void
    {
        $I->amOnPage('/pl/profile');

        $I->fillField('Imię', 'User name edited');
        $I->fillField('Nazwisko', 'User surname edited');
        $I->click('Wyślij');

        $I->seeResponseCodeIs(200);
        $I->seeNumberOfErrors(0);

        $I->canSeeAuthorExists('user@example.com', 'User name edited', 'User surname edited');

        $tooLongString = $I->createTooLongString();
        $I->fillField('Imię', $tooLongString);
        $I->fillField('Nazwisko', $tooLongString);
        $I->click('Wyślij');

        $I->seeNumberOfErrors(2);
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser('user@example.com', 'password12', 'User name', 'User surname');
    }
}
