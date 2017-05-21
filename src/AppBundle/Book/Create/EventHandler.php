<?php

namespace AppBundle\Book\Create;

use AppBundle\Book\Create\Event as CreateEvent;
use AppBundle\Book\Created\Event as CreatedEvent;
use AppBundle\Event\Recorder;
use Doctrine\Common\Persistence\ObjectManager;

class EventHandler
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Recorder
     */
    private $recorder;

    public function __construct(ObjectManager $manager, Recorder $recorder)
    {
        $this->manager = $manager;
        $this->recorder = $recorder;
    }

    public function handle(CreateEvent $event)
    {
        $book = $event->getData();
        $this->manager->persist($book);
        $this->manager->flush();
        $this->recorder->record(new CreatedEvent($book->getId()));
    }
}
