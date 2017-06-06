<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Routing\RedirectToEdit;
use AppBundle\Routing\RedirectToList;
use AppBundle\Scene\Delete\Command;
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

    public function __invoke(Scene $scene, $page)
    {
        $chapterId = $scene->getChapter() ? $scene->getChapter()->getId() : null;
        $this->commandBus->handle(new Command($scene->getId()));

        return $chapterId
            ? $this->editRedirector->createResponse('app_chapter_edit', $chapterId)
            : $this->listRedirector->createResponse('app_scene_list', $page)
        ;
    }
}
