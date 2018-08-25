<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Chapter\Create\Command;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;
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

    /**
     * @ParamConverter("book", class="Talesweaver\Domain\Book", options={"id" = "bookId", "isOptional" = true})
     */
    public function __invoke(Request $request, ?Book $book)
    {
        $bookId = $book ? $book->getId() : null;
        $form = $this->formFactory->create(CreateType::class, new DTO(), ['bookId' => $bookId]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $chapterId = Uuid::uuid4();
            $this->commandBus->handle(
                new Command($chapterId, new ShortText($form->getData()->getTitle()), $book)
            );

            return $this->redirector->createResponse('chapter_edit', $chapterId);
        }

        return $this->templating->createView($form, 'chapter/createForm.html.twig', ['bookId' => $bookId]);
    }
}
