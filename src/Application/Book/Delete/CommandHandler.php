<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Delete;

use Talesweaver\Domain\Books;

class CommandHandler
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function handle(Command $command)
    {
        $this->books->remove($command->getId());
    }
}
