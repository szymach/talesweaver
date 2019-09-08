<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class ActivateAdministratorHandler implements CommandHandlerInterface
{
    public function __invoke(ActivateAdministrator $command): void
    {
        $command->getAdministrator()->activate();
    }
}
