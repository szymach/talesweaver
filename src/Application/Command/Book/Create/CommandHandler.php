<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Create;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Book;
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
        $this->books->add(new Book($command->getId(), $command->getTitle(), $command->getAuthor()));
    }
}
