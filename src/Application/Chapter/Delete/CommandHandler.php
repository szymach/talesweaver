<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Delete;

use Talesweaver\Domain\Chapters;

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
        $this->chapters->remove($command->getId());
    }
}
