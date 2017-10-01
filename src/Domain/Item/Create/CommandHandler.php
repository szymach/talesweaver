<?php

namespace Domain\Item\Create;

use AppBundle\Entity\Item;
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
            new Item($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
