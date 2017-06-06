<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Event\Create\Command;
use AppBundle\Event\DTO;
use AppBundle\Form\Event\EventType;
use AppBundle\Templating\Event\CreateView;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CreateController
{
    /**
     * @var CreateView
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
        CreateView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Scene $scene, string $model)
    {
        $event = new DTO($scene);
        $form = $this->formFactory->create(
            EventType::class,
            $event,
            [
                'scene' => $scene,
                'model' => SceneEvents::getEventForm($model),
                'attr' => [
                    'action' => $this->router->generate(
                        'app_event_add',
                        ['id' => $scene->getId(), 'model' => $model]
                    ),
                    'class' => 'js-form'
                ]
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $id = Uuid::uuid4();
            $this->commandBus->handle(new Command($id, $form->getData()));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form);
    }
}
