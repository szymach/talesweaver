<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Delete;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Item;

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
            $this->manager->getRepository(Item::class)->find($command->getId())
        );
    }
}
