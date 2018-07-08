<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Create;

use Talesweaver\Domain\Character;
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
