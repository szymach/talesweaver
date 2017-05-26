<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Delete\Command;
use AppBundle\Entity\Book;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class DeleteController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(MessageBus $commandBus, RouterInterface $router)
    {
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Book $book, $page)
    {
        $this->commandBus->handle(new Command($book));

        return new RedirectResponse(
            $this->router->generate('app_book_list', ['page' => $page])
        );
    }
}
