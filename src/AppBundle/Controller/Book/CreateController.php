<?php

declare(strict_types=1);

namespace AppBundle\Controller\Book;

use Domain\Book\Create\Command;
use AppBundle\Form\Book\CreateType;
use AppBundle\Routing\RedirectToEdit;
use AppBundle\Templating\SimpleFormView;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $form = $this->formFactory->create(CreateType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $bookId = Uuid::uuid4();
            $this->commandBus->handle(new Command($bookId, $form->getData()->getTitle()));

            return $this->redirector->createResponse('app_book_edit', $bookId);
        }

        return $this->templating->createView($form, 'book/createForm.html.twig');
    }
}
