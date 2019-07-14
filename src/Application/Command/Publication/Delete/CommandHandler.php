<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Publication\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Publications;

final class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Publications
     */
    private $publications;

    public function __construct(Publications $publications)
    {
        $this->publications = $publications;
    }

    public function __invoke(Command $command): void
    {
        $this->publications->remove($command->getId());
    }
}
