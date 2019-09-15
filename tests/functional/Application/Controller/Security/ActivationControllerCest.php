<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

final class ActivationControllerCest
{
    public function userActivation(FunctionalTester $I): void
    {
        $author = $I->getAuthor('user@example.com', 'password', false);
        $I->amOnPage(sprintf('/pl/activate/%s', (string) $author->getActivationToken()));
        $I->canSeeCurrentUrlEquals('/pl/login');

        $I->assertTrue($author->isActive());
        $I->canSeeAlert('Pomyślnie aktywowano konto "user@example.com"! Możesz się teraz zalogować.');
    }
}
