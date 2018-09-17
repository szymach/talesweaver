<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Delete;

use Talesweaver\Domain\Scenes;

class CommandHandler
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $scenes)
    {
        $this->scenes = $scenes;
    }

    public function handle(Command $command): void
    {
        $this->scenes->remove($command->getId());
    }
}
