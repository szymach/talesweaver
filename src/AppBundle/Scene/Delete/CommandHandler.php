<?php

namespace AppBundle\Scene\Delete;

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
        $this->manager->remove(
            $this->manager->getRepository(Scene::class)->find($command->getId())
        );
        $this->manager->flush();
    }
}
