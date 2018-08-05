<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Create;

use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
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
        $description = $command->getData()->getDescription();
        $avatar = $command->getData()->getAvatar();
        $this->manager->persist(
            new Item(
                $command->getId(),
                $command->getData()->getScene(),
                new ShortText($command->getData()->getName()),
                null !== $description ? new LongText($description) : null,
                null !== $avatar ? new File($avatar) : null,
                $command->getAuthor()
            )
        );
    }
}
