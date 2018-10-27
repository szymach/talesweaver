<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Item\Edit\Command;
use Talesweaver\Application\Command\Item\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Item\Edit;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ItemResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var ItemResolver
     */
    private $itemResolver;

    /**
     * @var ApiResponseFactoryInterface
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
        ItemResolver $itemResolver,
        ApiResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->itemResolver = $itemResolver;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $item = $this->itemResolver->fromRequest($request);
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
            return $this->processFormDataAndRedirect($item, $formHandler->getData());
        }

        return $this->responseFactory->form(
            'partial\simpleForm.html.twig',
            ['form' => $formHandler->createView(), 'title' => 'item.header.edit'],
            $formHandler->displayErrors()
        );
    }

    private function processFormDataAndRedirect(Item $item, DTO $dto): ResponseInterface
    {
        $avatar = $dto->getAvatar();
        $this->commandBus->dispatch(new Command(
            $item,
            new ShortText($dto->getName()),
            LongText::fromNullableString($dto->getDescription()),
            null !== $avatar ? new File($avatar) : null
        ));

        return $this->responseFactory->success();
    }
}
