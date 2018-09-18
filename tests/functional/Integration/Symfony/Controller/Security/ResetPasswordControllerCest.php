<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

class ResetPasswordControllerCest
{
    private const LOGIN_ROUTE = 'login';
    private const REQUEST_ROUTE = 'password_reset_request';
    private const RESET_ROUTE = 'password_reset_change';
    private const REQUEST_FORM = 'form[name="reset_password_request"]';
    private const CHANGE_FORM = 'form[name="reset_password_change"]';

    private const EMAIL_FIELD = 'Email';
    private const REQUEST_SUBMIT = 'Wyślij';
    private const LOGIN = 'Logowanie';

    private const FIRST_PASSWORD = 'Nowe hasło';
    private const SECOND_PASSWORD = 'Powtórz nowe hasło';
    private const CHANGE_SUBMIT = 'Zmień hasło';

    private const PASSWORD_FIELD = 'Hasło';
    private const NEW_PASSWORD = 'nowe_haslo_123';
    private const LOGIN_SUBMIT = 'Zaloguj';
    private const DASHBOARD_ROUTE = 'index';

    public function resetPasswordFormRequestView(FunctionalTester $I)
    {
        $I->canSeeIAmOnRouteLocale(self::REQUEST_ROUTE);

        $I->seeElement(self::REQUEST_FORM);
        $I->see(self::EMAIL_FIELD);
        $I->see(self::REQUEST_SUBMIT);
        $I->see(self::LOGIN);
    }

    public function resetPasswordFormRequestSubmit(FunctionalTester $I)
    {
        $I->canSeeIAmOnRouteLocale(self::REQUEST_ROUTE);

        $I->fillField(self::EMAIL_FIELD, (string) $I->getAuthor()->getEmail());
        $I->click(self::REQUEST_SUBMIT);
        $I->canSeeCurrentUrlEquals($I->createUrl(self::LOGIN_ROUTE));
        $I->canSeeAlert(
            'Jeżeli podany adres email był prawidłowy, przesłano na niego email'
            . ' z instrukcjami zmiany hasła.'
        );

        $author = $I->getAuthor();
        $I->canSeeResetPasswordTokenGenerated($author);
        $I->canSeeIAmOnRouteLocale(self::RESET_ROUTE, [
            'code' => (string) $author->getPasswordResetToken()
        ]);
        $I->seeElement(self::CHANGE_FORM);
        $I->see(self::FIRST_PASSWORD);
        $I->see(self::SECOND_PASSWORD);
        $I->see(self::CHANGE_SUBMIT);
        $I->see(self::LOGIN);

        $I->fillField(self::FIRST_PASSWORD, self::NEW_PASSWORD);
        $I->fillField(self::SECOND_PASSWORD, self::NEW_PASSWORD);
        $I->click(self::CHANGE_SUBMIT);
        $I->canSeeCurrentUrlEquals($I->createUrl(self::LOGIN_ROUTE));
        $I->canSeeAlert(
            'Pomyślnie zmieniono hasło do konta. Możesz się teraz nim zalogować'
            . ' do aplikacji.'
        );
        $I->fillField(self::EMAIL_FIELD, FunctionalTester::AUTHOR_EMAIL);
        $I->fillField(self::PASSWORD_FIELD, self::NEW_PASSWORD);
        $I->click(self::LOGIN_SUBMIT);

        $I->canSeeCurrentUrlEquals($I->createUrl(self::DASHBOARD_ROUTE));
    }
}
