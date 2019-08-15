<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Scenes;

final class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $scenes)
    {
        $this->scenes = $scenes;
    }

    public function __invoke(Command $command): void
    {
        $this->scenes->remove($command->getScene());
    }
}
