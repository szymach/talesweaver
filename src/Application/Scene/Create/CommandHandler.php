<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Create;

use Talesweaver\Domain\Scene;
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
