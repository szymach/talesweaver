<?php

namespace AppBundle\Location\RemoveFromScene;

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
            $command->perform();
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $e) {
            $this->manager->rollback();
            throw $e;
        }
    }
}
