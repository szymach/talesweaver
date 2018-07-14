<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Bus\Command;

class ResetPasswordHandler
{
    public function handle(ResetPassword $command): void
    {
        $token = $command->getToken();
        $token->getUser()->setPassword(
            password_hash($command->getPassword(), PASSWORD_BCRYPT)
        );
        $token->deactivate();
    }
}
