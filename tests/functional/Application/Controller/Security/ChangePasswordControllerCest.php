<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Application\Controller\Security;

use Talesweaver\Tests\FunctionalTester;

final class ChangePasswordControllerCest
{
    public function changePasswordFormView(FunctionalTester $I): void
    {
        $I->canSeeIAmOnRouteLocale('change_password');
        $I->canSeeInTitle('Zmiana hasła');
        $I->canSeeElement('form[name="change_password"]');
        $I->canSee('Aktualne hasło');
        $I->canSee('Nowe hasło');
        $I->canSee('Wyślij');
    }

    public function successfullPasswordChange(FunctionalTester $I): void
    {
        $I->canSeeIAmOnRouteLocale('change_password');
        $I->fillField('Aktualne hasło', 'password');
        $I->fillField('Nowe hasło', 'newPassword123');
        $I->fillField('Powtórz nowe hasło', 'newPassword123');
        $I->click('Wyślij');
        $I->canSeeCurrentUrlEquals($I->createUrl('login'));
        $I->canSeeAlert('Pomyślnie zmieniono hasło do aplikacji. Wymagane jest ponowne zalogowanie.');
        $I->fillField('Email', 'user@example.com');
        $I->fillField('Hasło', 'newPassword123');
        $I->click('Zaloguj');
        $I->canSeeIAmOnRouteLocale('index');
    }

    public function incorrectPasswordSubmit(FunctionalTester $I): void
    {
        $I->canSeeIAmOnRouteLocale('change_password');
        $I->click('Wyślij');
        $I->canSeeCurrentUrlEquals($I->createUrl('change_password'));
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość powinna być aktualnym hasłem użytkownika.', 'change_password[currentPassword]');
        $I->seeError('Ta wartość nie powinna być pusta.', 'change_password[newPassword][first]');

        $I->fillField('Aktualne hasło', 'bledneHaslo');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość powinna być aktualnym hasłem użytkownika.', 'change_password[currentPassword]');
        $I->click('Wyślij');

        $I->fillField('Nowe hasło', 'newPassword123');
        $I->fillField('change_password[newPassword][first]', '12345');
        $I->click('Wyślij');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość jest nieprawidłowa.', 'change_password[newPassword][first]');

        $I->fillField('change_password[newPassword][first]', 'newPassword123');
        $I->click('Wyślij');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość jest nieprawidłowa.', 'change_password[newPassword][first]');

        $I->fillField('change_password[newPassword][first]', 'newPassword123');
        $I->fillField('change_password[newPassword][second]', 'newPassword321');
        $I->click('Wyślij');
        $I->seeNumberOfErrors(2);
        $I->seeError('Ta wartość jest nieprawidłowa.', 'change_password[newPassword][first]');
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser('user@example.com', 'password');
    }
}
