<?php

declare(strict_types=1);

namespace App\Controller\Book;

use App\Entity\Book;
use App\Routing\RedirectToList;
use Domain\Book\Delete\Command;
use SimpleBus\Message\Bus\MessageBus;

class DeleteController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RedirectToList
     */
    private $redirector;

    public function __construct(MessageBus $commandBus, RedirectToList $redirector)
    {
        $this->commandBus = $commandBus;
        $this->redirector = $redirector;
    }

    public function __invoke(Book $book, $page)
    {
        $this->commandBus->handle(new Command($book));

        return $this->redirector->createResponse('book_list', $page);
    }
}
