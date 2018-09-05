<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Item\Edit\Command;
use Talesweaver\Application\Item\Edit\DTO;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Item\EditType;
use Talesweaver\Integration\Symfony\Templating\Item\FormView;

class EditController
{
    /**
     * @var FormView
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commmandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commmandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commmandBus = $commmandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Item $item): ResponseInterface
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($item),
            [
                'action' => $this->responseFactory->generate('item_edit', ['id' => $item->getId()]),
                'itemId' => $item->getId()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processForDataAndCreateResponse($item, $form->getData());
        }

        return $this->templating->createView($form, 'item.header.edit');
    }

    private function processForDataAndCreateResponse(Item $item, DTO $dto): ResponseInterface
    {
        $this->commmandBus->handle(new Command(
            $item,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
