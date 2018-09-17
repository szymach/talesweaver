<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Create;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;

class CommandHandler implements CommandHandlerInterface
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
        $chapter = $command->getChapter();
        $scene = new Scene(
            $command->getId(),
            $command->getTitle(),
            $chapter,
            $command->getAuthor()
        );

        if (null !== $chapter) {
            $chapter->addScene($scene);
        }

        $this->scenes->add($scene);
    }
}
