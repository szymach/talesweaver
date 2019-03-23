<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Translation\TranslatorInterface;
use Talesweaver\Integration\Symfony\Security\User;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private const TEST_ENVIRONMENT = 'test';
    private const TEST_CYPRESS_ENVIRONMENT = 'test_cypress';

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $environment;

    public function __construct(
        CsrfTokenManagerInterface $csrfTokenManager,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator,
        string $environment
    ) {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->translator = $translator;
        $this->environment = $environment;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return $this->redirectTo('login', $request->getLocale());
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (false === $this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            throw new BadCredentialsException();
        }

        if (false === $user instanceof User) {
            throw new BadCredentialsException();
        }

        if (false === $user->getAuthor()->isActive()) {
            throw new CustomUserMessageAuthenticationException(sprintf(
                'User "%s" is inactive',
                (string) $user->getAuthor()->getEmail()
            ));
        }

        return true;
    }

    public function getCredentials(Request $request)
    {
        $this->validateCsrfToken($request);

        $email = $request->request->get('_email');
        $password = $request->request->get('_password');

        $this->getSession($request)->set(Security::LAST_USERNAME, $email);

        return ['email' => $email, 'password' => $password];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->getSession($request)->set(
            Security::AUTHENTICATION_ERROR,
            $this->translator->trans($exception->getMessageKey(), $exception->getMessageData(), 'security')
        );

        return $this->redirectTo('index', $request->getLocale());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->redirectTo('index', $request->getLocale());
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === sprintf('/%s/login', $request->getLocale())
            && true === $request->isMethod(Request::METHOD_POST)
        ;
    }

    protected function getLoginUrl(): string
    {
        // unused
    }

    private function getSession(Request $request): SessionInterface
    {
        $session = $request->getSession();
        Assertion::notNull($session);

        return $session;
    }

    private function validateCsrfToken(Request $request): void
    {
        if (true === in_array($this->environment, [self::TEST_ENVIRONMENT, self::TEST_CYPRESS_ENVIRONMENT], true)) {
            return;
        }

        $csrfToken = $request->request->get('_csrf_token');
        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }
    }

    private function redirectTo(string $route, string $locale): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($route, ['_locale' => $locale]));
    }
}
