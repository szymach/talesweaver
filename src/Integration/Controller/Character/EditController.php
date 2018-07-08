<?php

declare(strict_types=1);

namespace Integration\Controller\Character;

use Integration\Form\Character\EditType;
use Integration\Templating\Character\FormView;
use Application\Character\Edit\Command;
use Application\Character\Edit\DTO;
use Domain\Character;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

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

    public function __invoke(Request $request, Character $character)
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($character),
            ['action' => $this->router->generate('character_edit', ['id' => $character->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commmandBus->handle(new Command($form->getData(), $character));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form, 'character.header.edit');
    }
}
