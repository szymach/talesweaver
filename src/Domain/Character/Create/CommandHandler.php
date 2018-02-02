<?php

declare(strict_types=1);

namespace Domain\Character\Create;

use App\Entity\Character;
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
            new Character(
                $command->getId(),
                $command->getData()->getScene(),
                $command->getData()->getName(),
                $command->getData()->getDescription(),
                $command->getData()->getAvatar(),
                $command->getUser()
            )
        );
    }
}
