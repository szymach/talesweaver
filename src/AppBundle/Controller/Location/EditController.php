<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;
use AppBundle\Form\Location\LocationType;
use AppBundle\Pagination\LocationPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        ObjectManager $manager,
        LocationPaginator $pagination,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
        $this->router = $router;
    }

    public function editAction(Request $request, Location $location)
    {
        $form = $this->formFactory->create(
            LocationType::class,
            $location,
            ['action' => $this->router->generate('app_location_edit', ['id' => $location->getId()])
        ]);

        $result = true;
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
        } elseif ($form->isSubmitted()) {
            $result = false;
        }

        return new JsonResponse(
            [
                'form' => $this->templating->render(
                    'partial\simpleForm.html.twig',
                    ['form' => $form->createView(), 'h2Title' => 'location.header.edit']
                )
            ],
            $result ? 200 : 400
        );
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function addToSceneAction(Scene $scene, Location $location)
    {
        $scene->addLocation($location);
        $this->manager->flush();
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getForScene($scene, 1),
                    'scene' => $scene
                ]
            )]
        );
    }
}
