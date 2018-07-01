<?php

declare(strict_types=1);

namespace Application\Scene\Create;

use Domain\Scene;
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
        $chapter = $command->getData()->getChapter();
        $scene = new Scene(
            $command->getId(),
            $command->getData()->getTitle(),
            $command->getData()->getChapter(),
            $command->getUser()
        );
        if (null !== $chapter) {
            $chapter->addScene($scene);
        }

        $this->manager->persist($scene);
    }
}