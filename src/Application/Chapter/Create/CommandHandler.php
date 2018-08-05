<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Create;

use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function handle(Command $command): void
    {
        $this->chapters->add(
            new Chapter(
                $command->getId(),
                new ShortText($command->getData()->getTitle()),
                $command->getData()->getBook(),
                $command->getAuthor()
            )
        );
    }
}
