<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Scene;
use AppBundle\Form\Location\CreateType;
use AppBundle\Location\Create\Command;
use AppBundle\Location\Create\DTO;
use AppBundle\Templating\Location\FormView;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CreateController
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
    private $commandBus;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Scene $scene)
    {
        $form = $this->formFactory->create(
            CreateType::class,
            new DTO($scene),
            ['action' => $this->router->generate('app_location_new', ['id' => $scene->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command(Uuid::uuid4(), $form->getData()));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form, 'location.header.new');
    }
}
