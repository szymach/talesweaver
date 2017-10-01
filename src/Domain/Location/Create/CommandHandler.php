<?php

namespace Domain\Location\Create;

use AppBundle\Entity\Location;
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
            new Location($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
