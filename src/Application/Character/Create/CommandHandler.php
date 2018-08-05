<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Create;

use Talesweaver\Domain\Character;
use Talesweaver\Domain\Characters;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

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
        $description = $command->getData()->getDescription();
        $avatar = $command->getData()->getAvatar();
        $this->characters->add(
            new Character(
                $command->getId(),
                $command->getData()->getScene(),
                new ShortText($command->getData()->getName()),
                null !== $description ? new LongText($description) : null,
                null !== $avatar ? new File($avatar) : null,
                $command->getAuthor()
            )
        );
    }
}
