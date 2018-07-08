<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Delete;

use Talesweaver\Domain\Scene;
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
            $this->manager->getRepository(Scene::class)->find($command->getId())
        );
    }
}
