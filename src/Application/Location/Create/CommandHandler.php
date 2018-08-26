<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Create;

use Talesweaver\Domain\Location;
use Talesweaver\Domain\Locations;

class CommandHandler
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function handle(Command $command): void
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
