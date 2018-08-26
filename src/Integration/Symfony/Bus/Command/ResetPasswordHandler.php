<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Command;

class ResetPasswordHandler
{
    public function handle(ResetPassword $command): void
    {
        $token = $command->getToken();
        $token->getUser()->setPassword($command->getPassword());
        $token->deactivate();
    }
}
