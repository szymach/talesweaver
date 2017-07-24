<?php

namespace AppBundle\Event\Create;

use AppBundle\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class CommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Command $command)
    {
        $this->manager->beginTransaction();
        try {
            $this->manager->persist(new Event($command->getId(), $command->getData()));
            $this->manager->flush();
        } catch (Throwable $ex) {
            $this->manager->rollback();
            throw $ex;
        }
    }
}
