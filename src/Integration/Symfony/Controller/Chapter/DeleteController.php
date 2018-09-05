<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Chapter\Delete\Command;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Routing\RedirectToEdit;
use Talesweaver\Integration\Symfony\Routing\RedirectToList;

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

    public function __invoke(
        ServerRequestInterface $request,
        Chapter $chapter,
        int $page
    ): ResponseInterface {
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId(): null;
        $this->commandBus->handle(new Command($chapter));

        if ($request->isXmlHttpRequest()) {
            return $this->responseFactory->toJson(['success' => true]);
        }

        return $bookId
            ? $this->editRedirector->createResponse('book_edit', $bookId)
            : $this->listRedirector->createResponse('chapter_list', $page)
        ;
    }
}
