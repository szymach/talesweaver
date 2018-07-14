<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Book\Delete\Command;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Routing\RedirectToList;

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
