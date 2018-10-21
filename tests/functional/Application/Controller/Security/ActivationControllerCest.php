<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Assert\Assertion;
use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Module\AuthorModule;

class ActivationControllerCest
{
    public function userActivation(FunctionalTester $I)
    {
        $author = $I->getAuthor(AuthorModule::AUTHOR_EMAIL, AuthorModule::AUTHOR_PASSWORD, false);
        $I->amOnPage(sprintf('/pl/activate/%s', (string) $author->getActivationToken()));
        $I->canSeeCurrentUrlEquals('/pl/login');
        Assertion::eq(true, $author->isActive());
        $I->canSeeAlert(sprintf(
            'Pomyślnie aktywowano konto "%s"! Możesz się teraz zalogować.',
            (string) $author->getEmail()
        ));
    }
}
