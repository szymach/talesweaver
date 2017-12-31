<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Entity\User\ActivationToken;
use App\Entity\User\PasswordResetToken;
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
