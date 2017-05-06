<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Form\Scene\EditType;
use AppBundle\Pagination\Scene\SceneAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class EditController
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
     * @var SceneAggregate
     */
    private $pagination;

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
        SceneAggregate $pagination,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->pagination = $pagination;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function editAction(Request $request, Scene $scene)
    {
        $form = $this->formFactory->create(EditType::class, $scene);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'form' => $this->templating->render(
                    'partial/simpleForm.html.twig',
                    ['form' => $form->createView()]
                )
            ]);
        }

        return $this->templating->renderResponse(
            'scene/form.html.twig',
            [
                'form' => $form->createView(),
                'characters' => $this->pagination->getCharactersForScene($scene, 1),
                'items' => $this->pagination->getItemsForScene($scene, 1),
                'locations' => $this->pagination->getLocationsForScene($scene, 1),
                'scene' => $scene,
                'eventModels' => SceneEvents::getAllEvents(),
                'events' => $this->pagination->getEventsForScene($scene, 1)
            ]
        );
    }
}
