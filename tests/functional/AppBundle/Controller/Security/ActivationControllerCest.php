<?php

namespace Tests\AppBundle\Controller\Security;

use Assert\Assertion;
use FunctionalTester;

class ActivationControllerCest
{
    public function userActivation(FunctionalTester $I)
    {
        $user = $I->getUser(false);
        $I->amOnPage(sprintf('/pl/activate/%s', (string) $user->getActivationToken()));
        $I->canSeeCurrentUrlEquals('/pl/login');
        Assertion::eq(true, $user->isActive());
        $I->canSeeAlert(sprintf(
            'Pomyślnie aktywowano konto "%s"! Możesz się teraz zalogować.',
            $user->getUsername()
        ));
    }
}