<?php

namespace AppBundle\Book\Edit;

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
        $this->manager->flush();
        $this->manager->refresh($event->getData());
    }
}
