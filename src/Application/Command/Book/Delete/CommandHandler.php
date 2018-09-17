<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Books;

class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(Command $command): void
    {
        $this->books->remove($command->getId());
    }
}
