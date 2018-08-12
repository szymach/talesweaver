<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Create;

use Talesweaver\Domain\Item;
use Talesweaver\Domain\Items;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function handle(Command $command): void
    {
        $description = $command->getData()->getDescription();
        $avatar = $command->getData()->getAvatar();
        $this->items->add(
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
