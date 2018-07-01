<?php

declare(strict_types=1);

namespace App\Controller\Event;

use App\Enum\SceneEvents;
use App\Form\Event\CreateType;
use App\Templating\Event\FormView;
use Domain\Entity\Scene;
use Domain\Event\Create\Command;
use Domain\Event\Create\DTO;
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
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Scene $scene, string $model)
    {
        $form = $this->formFactory->create(
            CreateType::class,
            new DTO($scene),
            [
                'scene' => $scene,
                'model' => SceneEvents::getEventForm($model),
                'action' => $this->router->generate(
                    'event_add',
                    ['id' => $scene->getId(), 'model' => $model]
                )
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
