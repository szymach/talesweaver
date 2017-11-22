<?php

declare(strict_types=1);

declare(strict_types=1);

namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Entity\User\ActivationToken;
use AppBundle\Entity\User\PasswordResetToken;
use function random_bytes;

class TokenGenerator
{
    public function generateUserActivationToken(User $user): ActivationToken
    {
        return new ActivationToken($user, $this->generateCode());
    }

    public function generatePasswordActivationToken(User $user): PasswordResetToken
    {
        return new PasswordResetToken($user, $this->generateCode());
    }

    private function generateCode(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
