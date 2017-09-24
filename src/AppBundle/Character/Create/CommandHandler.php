<?php

namespace AppBundle\Character\Create;

use AppBundle\Entity\Character;
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
            new Character($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
