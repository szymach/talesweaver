<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Chapter\Edit\Command;
use Talesweaver\Application\Chapter\Edit\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Form\Chapter\EditType;
use Talesweaver\Integration\Symfony\Routing\RedirectToEdit;
use Talesweaver\Integration\Symfony\Templating\Chapter\EditView;

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
        $form = $this->formFactory->create(EditType::class, $dto, [
            'chapterId' => $chapter->getId(),
            'bookId' => null !== $chapter->getBook() ? $chapter->getBook()->getId() : null
        ]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($dto, $chapter));

            return $this->redirector->createResponse('chapter_edit', $chapter->getId());
        }

        return $this->templating->createView($form, $chapter);
    }
}
