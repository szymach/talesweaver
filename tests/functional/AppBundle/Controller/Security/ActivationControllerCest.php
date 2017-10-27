<?php

namespace Tests\AppBundle\Controller\Security;

use Assert\Assert;
use FunctionalTester;

class ActivationControllerCest
{
    public function userActivation(FunctionalTester $I)
    {
        $user = $I->getUser(false);
        $I->amOnPage(sprintf('/pl/activate/%s', (string) $user->getActivationCode()));
        $I->canSeeInCurrentUrl('/pl/login');
        Assert::that($user->isActive())->eq(true);
    }

}
