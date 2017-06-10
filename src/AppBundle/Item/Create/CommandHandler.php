<?php

namespace AppBundle\Item\Create;

use AppBundle\Entity\Item;
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
            $item = new Item($command->getId(), $command->getData());
            $this->manager->persist($item);
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $exception) {
            $this->manager->rollback();
            throw $exception;
        }
    }
}
