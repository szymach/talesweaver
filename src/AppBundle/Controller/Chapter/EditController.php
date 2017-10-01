<?php

namespace AppBundle\Controller\Chapter;

use Domain\Chapter\Edit\Command;
use Domain\Chapter\Edit\DTO;
use AppBundle\Entity\Chapter;
use AppBundle\Form\Chapter\EditType;
use AppBundle\Routing\RedirectToEdit;
use AppBundle\Templating\Chapter\EditView;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class EditController
{
    /**
     * @var EditView
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RedirectToEdit
     */
    private $redirector;

    public function __construct(
        EditView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RedirectToEdit $redirector
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->redirector = $redirector;
    }

    public function __invoke(Request $request, Chapter $chapter)
    {
        $dto = new DTO($chapter);
        $form = $this->formFactory->create(EditType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($dto, $chapter));

            return $this->redirector->createResponse('app_chapter_edit', $chapter->getId());
        }

        return $this->templating->createView($form, $chapter);
    }
}
