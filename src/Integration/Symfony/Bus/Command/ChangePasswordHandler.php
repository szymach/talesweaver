<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Command;

class ChangePasswordHandler
{
    public function handle(ChangePassword $command): void
    {
        $command->getUser()->setPassword(
            password_hash($command->getNewPassword(), PASSWORD_BCRYPT)
        );
    }
}
