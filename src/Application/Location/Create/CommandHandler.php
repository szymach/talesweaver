<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Create;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Location;

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
        $this->manager->persist(
            new Location(
                $command->getId(),
                $command->getData()->getScene(),
                $command->getData()->getName(),
                $command->getData()->getDescription(),
                $command->getData()->getAvatar(),
                $command->getAuthor()
            )
        );
    }
}
