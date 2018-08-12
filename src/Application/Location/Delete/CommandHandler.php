<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Delete;

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
        $this->locations->remove($command->getId());
    }
}
