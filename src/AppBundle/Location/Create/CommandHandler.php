<?php

namespace AppBundle\Location\Create;

use AppBundle\Entity\Location;
use Doctrine\Common\Persistence\ObjectManager;
use Throwable;

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
        $this->manager->beginTransaction();
        try {
            $location = new Location($command->getId(), $command->getData());
            $this->manager->persist($location);
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $exception) {
            $this->manager->rollback();
            throw $exception;
        }
    }
}
