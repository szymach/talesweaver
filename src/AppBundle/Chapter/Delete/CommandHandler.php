<?php

namespace AppBundle\Chapter\Delete;

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
        $this->manager->remove(
            $this->manager->getRepository(Chapter::class)->find($command->getId())
        );
        $this->manager->flush();
    }
}
