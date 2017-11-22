<?php

declare(strict_types=1);

namespace Domain\Scene\Create;

use AppBundle\Entity\Scene;
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
            new Scene($command->getId(), $command->getData(), $command->getUser())
        );
    }
}
