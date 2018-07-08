<?php

declare(strict_types=1);

namespace Integration\Controller\Location;

use Integration\Form\Location\EditType;
use Integration\Templating\Location\FormView;
use Domain\Location;
use Application\Location\Edit\Command;
use Application\Location\Edit\DTO;
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

    public function __invoke(Request $request, Location $location)
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($location),
            ['action' => $this->router->generate('location_edit', ['id' => $location->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commmandBus->handle(new Command($form->getData(), $location));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form, 'location.header.edit');
    }
}
