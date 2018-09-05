<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Book\Create\Command;
use Talesweaver\Application\Book\Create\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Book\CreateType;
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SimpleFormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = $this->formFactory->create(CreateType::class, new DTO());
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($form->getData());
        }

        return $this->templating->createView($form, 'book/createForm.html.twig');
    }

    private function processFormDataAndRedirect(DTO $dto): ResponseInterface
    {
        $bookId = Uuid::uuid4();
        $this->commandBus->handle(new Command($bookId, new ShortText($dto->getTitle())));

        return $this->responseFactory->redirectToRoute('book_edit', $bookId);
    }
}
