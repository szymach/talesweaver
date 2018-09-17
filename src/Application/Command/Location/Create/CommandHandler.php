<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Create;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Locations;

class CommandHandler implements CommandHandlerInterface
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
        $this->locations->add(
            new Location(
                $command->getId(),
                $command->getScene(),
                $command->getName(),
                $command->getDescription(),
                $command->getAvatar(),
                $command->getAuthor()
            )
        );
    }
}
