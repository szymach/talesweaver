<?php

declare(strict_types=1);

namespace Application\Event\Create;

use Domain\Event;
use Doctrine\Common\Persistence\ObjectManager;

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
            new Event(
                $command->getId(),
                $command->getData()->getName(),
                $command->getData()->getModel(),
                $command->getData()->getScene(),
                $command->getUser()
            )
        );
    }
}