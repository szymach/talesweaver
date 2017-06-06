<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Chapter\Delete\Command;
use AppBundle\Entity\Chapter;
use AppBundle\Routing\RedirectToEdit;
use AppBundle\Routing\RedirectToList;
use SimpleBus\Message\Bus\MessageBus;

class DeleteController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RedirectToEdit
     */
    private $editRedirector;

    /**
     * @var RedirectToList
     */
    private $listRedirector;

    public function __construct(
        MessageBus $commandBus,
        RedirectToEdit $editRedirector,
        RedirectToList $listRedirector
    ) {
        $this->commandBus = $commandBus;
        $this->editRedirector = $editRedirector;
        $this->listRedirector = $listRedirector;
    }

    public function __invoke(Chapter $chapter, $page)
    {
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId() : null;
        $this->commandBus->handle(new Command($chapter->getId()));

        return $bookId
            ? $this->editRedirector->createResponse('app_book_edit', $bookId)
            : $this->listRedirector->createResponse('app_chapter_list', $page)
        ;
    }
}
