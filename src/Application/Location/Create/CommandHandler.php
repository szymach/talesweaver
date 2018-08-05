<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Create;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Command $command): void
    {
        $description = $command->getData()->getDescription();
        $this->manager->persist(
            new Location(
                $command->getId(),
                $command->getData()->getScene(),
                new ShortText($command->getData()->getName()),
                null !== $description ? new LongText($description) : null,
                $command->getData()->getAvatar(),
                $command->getAuthor()
            )
        );
    }
}
