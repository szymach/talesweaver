<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Form\Event\EventType;
use AppBundle\Pagination\EventPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @var EventPaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        RouterInterface $router,
        EventPaginator $pagination
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
        $this->pagination = $pagination;
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
                    'action' => $this->router->generate(
                        'app_event_add',
                        ['id' => $scene->getId(), 'model' => $model]
                    ),
                    'class' => 'js-form'
                ]
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $this->manager->persist($event);
            $this->manager->flush();
            $this->manager->refresh($event);

            return new JsonResponse([
                'list' => $this->templating->render(
                    'scene\events\list.html.twig',
                    [
                        'events' => $this->pagination->getForScene($scene, 1),
                        'eventModels' => SceneEvents::getAllEvents(),
                        'scene' => $scene
                    ]
                )
            ]);
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial/simpleForm.html.twig',
                ['form' => $form->createView()]
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }

    public function listAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\events\list.html.twig',
                [
                    'events' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene,
                    'eventModels' => SceneEvents::getAllEvents(),
                    'page' => $page
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("event", options={"id" = "event_id"})
     */
    public function deleteAction(Event $event, $page)
    {
        $scene = $event->getScene();
        $this->manager->remove($event);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\events\list.html.twig',
                [
                    'events' => $this->pagination->getForScene($scene, $page),
                    'eventModels' => SceneEvents::getAllEvents(),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
