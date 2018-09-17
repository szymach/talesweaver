<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\Create;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Characters;

class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Characters
     */
    private $characters;

    public function __construct(Characters $characters)
    {
        $this->characters = $characters;
    }

    public function __invoke(Command $command): void
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
