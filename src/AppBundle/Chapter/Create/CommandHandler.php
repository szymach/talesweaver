<?php

namespace AppBundle\Chapter\Create;

use AppBundle\Entity\Chapter;
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
            new Chapter($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
