<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Chapter\Delete\Command;
use AppBundle\Entity\Chapter;
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

    public function __invoke(Chapter $book, $page)
    {
        $this->commandBus->handle(new Command($book));

        return new RedirectResponse(
            $this->router->generate('app_chapter_list', ['page' => $page])
        );
    }
}
