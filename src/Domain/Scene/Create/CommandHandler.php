<?php

namespace Domain\Scene\Create;

use AppBundle\Entity\Scene;
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
            new Scene($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
