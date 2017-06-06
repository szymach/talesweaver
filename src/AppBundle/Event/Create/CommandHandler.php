<?php

namespace AppBundle\Event\Create;

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
        $event = new Event($command->getId(), $command->getData());
        $this->manager->persist($event);
        $this->manager->flush();
        $this->manager->refresh($event);
    }
}
