<?php

declare(strict_types=1);

namespace Domain\Location\Delete;

use Domain\Entity\Location;
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
            $this->manager->getRepository(Location::class)->find($command->getId())
        );
    }
}
