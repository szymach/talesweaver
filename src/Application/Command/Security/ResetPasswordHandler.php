<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class ResetPasswordHandler implements CommandHandlerInterface
{
    public function __invoke(ResetPassword $command): void
    {
        $token = $command->getToken();
        $token->getAuthor()->setPassword($command->getPassword());
        $token->deactivate();
    }
}
