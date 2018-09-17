<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\Delete;

use Talesweaver\Domain\Characters;

class CommandHandler
{
    /**
     * @var Characters
     */
    private $characters;

    public function __construct(Characters $characters)
    {
        $this->characters = $characters;
    }

    public function handle(Command $command): void
    {
        $this->characters->remove($command->getId());
    }
}
