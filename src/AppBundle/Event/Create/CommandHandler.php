<?php

namespace AppBundle\Event\Create;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Workflow\Event\Event;

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
        $this->manager->persist(new Event($command->getId(), $command->getData()));
    }
}
