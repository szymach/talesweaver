<?php

declare(strict_types=1);

namespace Domain\Chapter\Create;

use App\Entity\Chapter;
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
            new Chapter(
                $command->getId(),
                $command->getData()->getTitle(),
                $command->getData()->getBook(),
                $command->getUser()
            )
        );
    }
}
