<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

final class ResetPasswordControllerCest
{
    public function resetPasswordFormRequestView(FunctionalTester $I)
    {
        $I->canSeeIAmOnRouteLocale('password_reset_request');

        $I->seeElement('form[name="reset_password_request"]');
        $I->see('Email');
        $I->see('Wyślij');
        $I->see('Powrót do logowania');
    }

    public function resetPasswordFormRequestSubmit(FunctionalTester $I)
    {
        $I->getAuthor('test@example.com');
        $I->canSeeIAmOnRouteLocale('password_reset_request');

        $I->fillField('Email', 'test@example.com');
        $I->click('Wyślij');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->canSeeAlert(
            'Jeżeli podany adres email był prawidłowy, przesłano na niego wiadomość'
            . ' z instrukcją zmiany hasła.'
        );
        $I->seeAnEmailHasBeenSent('Bajkopisarz - resetowanie hasła', 'test@example.com');

        /** @var Author $author */
        $author = $I->getAuthor('test@example.com');
        $I->canSeeResetPasswordTokenGenerated($author);
        $I->canSeeIAmOnRouteLocale('password_reset_change', [
            'code' => (string) $author->getPasswordResetToken()
        ]);
        $I->seeElement('form[name="reset_password_change"]');
        $I->see('Nowe hasło');
        $I->see('Powtórz nowe hasło');
        $I->see('Zmień hasło');
        $I->see('Powrót do logowania');

        $I->fillField('Nowe hasło', 'nowe_haslo_123');
        $I->fillField('Powtórz nowe hasło', 'nowe_haslo_123');
        $I->click('Zmień hasło');
        $I->canSeeCurrentUrlEquals('/pl/login');
        $I->canSeeAlert(
            'Pomyślnie zmieniono hasło do konta. Możesz się teraz nim zalogować do aplikacji.'
        );
        $I->fillField('Email', 'test@example.com');
        $I->fillField('Hasło', 'nowe_haslo_123');
        $I->click('Zaloguj');

        $I->canSeeCurrentUrlEquals('/pl');
    }
}
