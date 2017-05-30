<?php

namespace AppBundle\Chapter\Create;

use AppBundle\Entity\Chapter;
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
            $chapter = new Chapter(
                $command->getId(),
                $command->getData()->getTitle(),
                $command->getData()->getBook()
            );
            $this->manager->persist($chapter);
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $exception) {
            $this->manager->rollback();
            throw $exception;
        }
    }
}
