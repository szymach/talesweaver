<?php

namespace AppBundle\Character\Create;

use AppBundle\Entity\Character;
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
            $character = new Character($command->getId(), $command->getData());
            $this->manager->persist($character);
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $exception) {
            $this->manager->rollback();
            throw $exception;
        }
    }
}
