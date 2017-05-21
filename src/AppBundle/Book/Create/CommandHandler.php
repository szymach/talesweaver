<?php

namespace AppBundle\Book\Create;

use AppBundle\Book\Create\Command;
use AppBundle\Book\Created\Event as CreatedEvent;
use AppBundle\Entity\Book;
use AppBundle\Event\Recorder;
use Doctrine\Common\Persistence\ObjectManager;

class CommandHandler
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

    public function handle(Command $command)
    {
        $book = new Book($command->getData()->getTitle());
        $this->manager->persist($book);
        $this->manager->flush();
        $this->recorder->record(new CreatedEvent($book->getId()));
    }
}
