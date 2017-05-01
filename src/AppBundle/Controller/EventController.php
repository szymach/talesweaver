<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Form\Event\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class EventController
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
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function formAction(Request $request, Scene $scene, $model)
    {
        $form = $this->formFactory->create(
            EventType::class,
            new Event($scene),
            [
                'scene' => $scene,
                'model' => SceneEvents::getEventForm($model),
                'attr' => [
                    'action' => $this->router->generate('app_scene_event', ['id' => $scene->getId(), 'model' => $model])
                ]
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->persist($form->getData());
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_scene_edit', ['id' => $scene->getId()])
            );
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial/simpleForm.html.twig',
                ['form' => $form->createView()]
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
