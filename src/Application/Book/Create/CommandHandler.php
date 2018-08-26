<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Create;

use Talesweaver\Domain\Book;
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

    public function handle(Command $command): void
    {
        $this->books->add(new Book($command->getId(), $command->getTitle(), $command->getAuthor()));
    }
}
