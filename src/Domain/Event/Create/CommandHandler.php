<?php

namespace Domain\Event\Create;

use AppBundle\Entity\Event;
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

    public function handle(Command $command)
    {
        $this->manager->persist(
            new Event($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
