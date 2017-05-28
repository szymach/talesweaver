<?php

namespace AppBundle\Book\Create;

use AppBundle\Book\Create\Command;
use AppBundle\Entity\Book;
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
            $book = new Book(
                $command->getId(),
                $command->getData()->getTitle()
            );
            $this->manager->persist($book);
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $exception) {
            $this->manager->rollback();
            throw $exception;
        }
    }
}
