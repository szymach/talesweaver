<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Administrators;

final class AddAdministratorHandler implements CommandHandlerInterface
{
    /**
     * @var Administrators
     */
    private $administrators;

    public function __construct(Administrators $administrators)
    {
        $this->administrators = $administrators;
    }

    public function __invoke(AddAdministrator $command): void
    {
        $this->administrators->add($command->getAdministrator());
    }
}
