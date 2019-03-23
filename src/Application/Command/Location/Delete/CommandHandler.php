<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Locations;

final class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(Command $command): void
    {
        $this->locations->remove($command->getId());
    }
}
