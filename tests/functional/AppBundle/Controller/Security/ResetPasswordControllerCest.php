<?php

namespace Tests\AppBundle\Controller\Security;

use FunctionalTester;

class ResetPasswordControllerCest
{
    public function resetPasswordFormRequestView(FunctionalTester $I)
    {
        $I->amOnPage('/pl/reset-password/request');
        $I->canSeeInCurrentUrl('/pl/reset-password/request');
        $I->seeResponseCodeIs(200);
        $I->seeElement('form[name="reset_password_request"]');
        $I->see('Email');
        $I->see('Wyślij');
        $I->see('Logowanie');
    }

    public function resetPasswordFormRequestSubmit(FunctionalTester $I)
    {
        $user = $I->getUser();
        $I->amOnPage('/pl/reset-password/request');
        $I->fillField('Email', $user->getUsername());
        $I->click('Wyślij');
        $I->amOnPage('/pl/reset-password/request');
        $I->seeResponseCodeIs(200);
        $I->canSeeResetPasswordTokenGenerated($user);
    }
}
