<?php

namespace AppBundle\Item\Delete;

use AppBundle\Entity\Item;
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
            $this->manager->remove(
                $this->manager->getRepository(Item::class)->find($command->getId())
            );
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $e) {
            $this->manager->rollback();
            throw $e;
        }
    }
}
