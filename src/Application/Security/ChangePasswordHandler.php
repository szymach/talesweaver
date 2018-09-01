<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

class ChangePasswordHandler
{
    public function handle(ChangePassword $command): void
    {
        $command->getAuthor()->setPassword($command->getNewPassword());
    }
}
