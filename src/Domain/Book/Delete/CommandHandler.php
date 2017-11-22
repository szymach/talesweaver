<?php

declare(strict_types=1);

namespace Domain\Book\Delete;

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

    public function handle(Command $command)
    {
        $this->manager->remove(
            $this->manager->getRepository(Book::class)->find($command->getId())
        );
    }
}
