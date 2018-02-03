<?php

declare(strict_types=1);

namespace Domain\Event\Delete;

use Domain\Entity\Event;
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
        $this->manager->remove(
            $this->manager->getRepository(Event::class)->find($command->getId())
        );
    }
}
