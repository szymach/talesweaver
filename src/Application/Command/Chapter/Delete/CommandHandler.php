<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Chapters;

class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(Command $command): void
    {
        $this->chapters->remove($command->getId());
    }
}
