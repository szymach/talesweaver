<?php

declare(strict_types=1);

namespace AppBundle\Security\CodeGenerator;

use AppBundle\Entity\User;
use AppBundle\Entity\UserActivationCode;

class ActivationCodeGenerator
{
    public function generate(User $user): UserActivationCode
    {
        return new UserActivationCode(
            $user,
            rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=')
        );
    }
}
