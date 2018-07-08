<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Delete;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Character;

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
            $this->manager->getRepository(Character::class)->find($command->getId())
        );
    }
}
