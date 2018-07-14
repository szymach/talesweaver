<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Chapter\Create\Command;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Form\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Routing\RedirectToEdit;
use Talesweaver\Integration\Symfony\Templating\SimpleFormView;

class CreateController
{
    /**
     * @var SimpleFormView
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
        SimpleFormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RedirectToEdit $redirector
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->redirector = $redirector;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(CreateType::class, new DTO());
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $chapterId = Uuid::uuid4();
            $this->commandBus->handle(new Command($chapterId, $form->getData()));

            return $this->redirector->createResponse('chapter_edit', $chapterId);
        }

        /* @var $book Book */
        $book = $form->get('book')->getData();
        return $this->templating->createView(
            $form,
            'chapter/createForm.html.twig',
            ['bookId' => $book ? $book->getId(): null]
        );
    }
}
