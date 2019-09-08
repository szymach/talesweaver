<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class DeactivateAdministratorHandler implements CommandHandlerInterface
{
    public function __invoke(DeactivateAdministrator $command): void
    {
        $command->getAdministrator()->deactivate();
    }
}
