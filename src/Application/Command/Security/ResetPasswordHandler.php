<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

class ResetPasswordHandler
{
    public function handle(ResetPassword $command): void
    {
        $token = $command->getToken();
        $token->getAuthor()->setPassword($command->getPassword());
        $token->deactivate();
    }
}
