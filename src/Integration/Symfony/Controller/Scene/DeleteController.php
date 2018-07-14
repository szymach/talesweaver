<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Scene\Delete\Command;
use Talesweaver\Domain\Scene;
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

    public function __invoke(Request $request, Scene $scene, $page)
    {
        $chapterId = $scene->getChapter() ? $scene->getChapter()->getId(): null;
        $this->commandBus->handle(new Command($scene));

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => true]);
        }

        return $chapterId
            ? $this->editRedirector->createResponse('chapter_edit', $chapterId)
            : $this->listRedirector->createResponse('scene_list', $page)
        ;
    }
}
