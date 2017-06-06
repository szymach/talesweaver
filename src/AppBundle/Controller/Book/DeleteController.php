<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Delete\Command;
use AppBundle\Entity\Book;
use AppBundle\Routing\RedirectToList;
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

        return $this->redirector->createResponse('app_book_list', $page);
    }
}
