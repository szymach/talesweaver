<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Item;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Item\Edit\Command;
use Talesweaver\Application\Item\Edit\DTO;
use Talesweaver\Domain\Item;
use Talesweaver\Integration\Form\Item\EditType;
use Talesweaver\Integration\Templating\Item\FormView;

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
            ['action' => $this->router->generate('item_edit', ['id' => $item->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commmandBus->handle(new Command($form->getData(), $item));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form, 'item.header.edit');
    }
}
