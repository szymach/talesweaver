<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Module\AuthorModule;

class ResetPasswordControllerCest
{
    public function resetPasswordFormRequestView(FunctionalTester $I)
    {
        $I->canSeeIAmOnRouteLocale('password_reset_request');

        $I->seeElement('form[name="reset_password_request"]');
        $I->see('Email');
        $I->see('Wyślij');
        $I->see('Logowanie');
    }

    public function resetPasswordFormRequestSubmit(FunctionalTester $I)
    {
        $I->getAuthor();
        $I->canSeeIAmOnRouteLocale('password_reset_request');

        $I->fillField('Email', AuthorModule::AUTHOR_EMAIL);
        $I->click('Wyślij');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->canSeeAlert(
            'Jeżeli podany adres email był prawidłowy, przesłano na niego email'
            . ' z instrukcjami zmiany hasła.'
        );

        /* @var $author Author */
        $author = $I->getAuthor();
        $I->canSeeResetPasswordTokenGenerated($author);
        $I->canSeeIAmOnRouteLocale('password_reset_change', [
            'code' => (string) $author->getPasswordResetToken()
        ]);
        $I->seeElement('form[name="reset_password_change"]');
        $I->see('Nowe hasło');
        $I->see('Powtórz nowe hasło');
        $I->see('Zmień hasło');
        $I->see('Logowanie');

        $I->fillField('Nowe hasło', 'nowe_haslo_123');
        $I->fillField('Powtórz nowe hasło', 'nowe_haslo_123');
        $I->click('Zmień hasło');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->canSeeAlert(
            'Pomyślnie zmieniono hasło do konta. Możesz się teraz nim zalogować'
            . ' do aplikacji.'
        );
        $I->fillField('Email', AuthorModule::AUTHOR_EMAIL);
        $I->fillField('Hasło', 'nowe_haslo_123');
        $I->click('Zaloguj');

        $I->canSeeCurrentUrlEquals('/pl');
    }
}
