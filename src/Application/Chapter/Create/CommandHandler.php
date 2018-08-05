<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Create;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

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
            new Chapter(
                $command->getId(),
                new ShortText($command->getData()->getTitle()),
                $command->getData()->getBook(),
                $command->getAuthor()
            )
        );
    }
}
