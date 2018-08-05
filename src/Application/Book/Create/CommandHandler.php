<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Create;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Book;
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
            new Book($command->getId(), new ShortText($command->getTitle()), $command->getAuthor())
        );
    }
}
