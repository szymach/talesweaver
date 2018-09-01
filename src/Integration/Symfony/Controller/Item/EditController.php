<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
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
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commmandBus,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commmandBus = $commmandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Item $item)
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($item),
            [
                'action' => $this->router->generate('item_edit', ['id' => $item->getId()]),
                'itemId' => $item->getId()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processForDataAndCreateResponse($item, $form->getData());
        }

        return $this->templating->createView($form, 'item.header.edit');
    }

    private function processForDataAndCreateResponse(Item $item, DTO $dto): Response
    {
        $this->commmandBus->handle(new Command(
            $item,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return new JsonResponse(['success' => true]);
    }
}
