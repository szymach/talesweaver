<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Item\Edit;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Item\Edit\Command;
use Talesweaver\Application\Item\Edit\DTO;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        MessageBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Item $item): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($item),
            [
                'action' => $this->urlGenerator->generate('item_edit', ['id' => $item->getId()]),
                'itemId' => $item->getId()
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processForDataAndCreateResponse($item, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial\simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'item.header.edit']
            )
        ], true === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processForDataAndCreateResponse(Item $item, DTO $dto): ResponseInterface
    {
        $this->commandBus->handle(new Command(
            $item,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
