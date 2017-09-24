<?php

namespace AppBundle\Character\Delete;

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

    public function handle(Command $command) : void
    {
        $this->manager->remove(
            $this->manager->getRepository(Character::class)->find($command->getId())
        );
    }
}
