<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use RuntimeException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Talesweaver\Application\Security\AuthenticationContext as ApplicationAuthenticationContext;

class AuthenticationContext implements ApplicationAuthenticationContext
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtilities;

    public function __construct(AuthenticationUtils $authenticationUtilities)
    {
        $this->authenticationUtilities = $authenticationUtilities;
    }

    public function lastProvidedUsername(): ?string
    {
        return $this->authenticationUtilities->getLastUsername();
    }

    public function lastError(): ?string
    {
        $error = $this->authenticationUtilities->getLastAuthenticationError();
        if (null === $error || true === is_string($error)) {
            $value = $error;
        } elseif (true === $error instanceof AuthenticationException) {
            $value = $error->getMessage();
        } else {
            throw new RuntimeException(sprintf(
                'Unable to read last authentication error from "%s"',
                is_object($error) ? get_class($error) : gettype($error)
            ));
        }
        return $value;
    }
}
