<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Location\Edit\Command;
use Talesweaver\Application\Location\Edit\DTO;
use Talesweaver\Domain\Location;
use Talesweaver\Integration\Symfony\Form\Location\EditType;
use Talesweaver\Integration\Symfony\Templating\Location\FormView;

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
