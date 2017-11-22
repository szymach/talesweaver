<?php

declare(strict_types=1);

namespace Domain\Book\Create;

use AppBundle\Entity\Book;
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

    public function handle(Command $command): void
    {
        $this->manager->persist(
            new Book($command->getId(), $command->getTitle(), $command->getUser())
        );
    }
}
