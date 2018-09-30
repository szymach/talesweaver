<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Item\Edit\Command;
use Talesweaver\Application\Command\Item\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Item\Edit;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Item\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
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
        QueryBus $queryBus,
        AuthorContext $authorContext,
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $item = $this->getItem($request->getAttribute('id'));
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
        ], false === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processForDataAndCreateResponse(Item $item, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $item,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getItem(?string $id): Item
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No item id!');
        }

        $uuid = Uuid::fromString($id);
        $item = $this->queryBus->query(new ById($uuid));
        if (false === $item instanceof Item
            || $this->authorContext->getAuthor() !== $item->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No item for id "%s"!', $uuid->toString()));
        }

        return $item;
    }
}
