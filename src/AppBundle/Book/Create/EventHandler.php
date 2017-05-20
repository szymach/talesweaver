<?php

namespace AppBundle\Book\Create;

use Doctrine\Common\Persistence\ObjectManager;

class EventHandler
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Event $event)
    {
        $this->manager->persist($event->getData());
        $this->manager->flush();
    }
}
