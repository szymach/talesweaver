<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Form\Event\EventType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CreateController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function __invoke(Request $request, Scene $scene, string $model)
    {
        $event = new Event($scene);
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
            $this->manager->persist($event);
            $this->manager->flush();
            $this->manager->refresh($event);

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial/simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'event.header.new']
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
