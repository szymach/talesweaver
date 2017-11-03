<?php

declare(strict_types=1);

namespace Domain\Security\Command;

class ChangePasswordHandler
{
    public function handle(ChangePassword $command): void
    {
        $token = $command->getToken();
        $token->getUser()->setPassword(
            password_hash($command->getPassword(), PASSWORD_BCRYPT)
        );
        $token->deactivate();
    }
}
