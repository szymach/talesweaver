<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Create;

use Talesweaver\Domain\Character;
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
        $this->characters->add(
            new Character(
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
