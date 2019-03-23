<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class ChangePasswordHandler implements CommandHandlerInterface
{
    public function __invoke(ChangePassword $command): void
    {
        $command->getAuthor()->setPassword($command->getNewPassword());
    }
}
