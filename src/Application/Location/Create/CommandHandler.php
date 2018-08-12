<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Create;

use Talesweaver\Domain\Location;
use Talesweaver\Domain\Locations;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

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
        $description = $command->getData()->getDescription();
        $avatar = $command->getData()->getAvatar();
        $this->locations->add(
            new Location(
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
