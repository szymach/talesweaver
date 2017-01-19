<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Location;
use AppBundle\Entity\Repository\LocationRepository;
use AppBundle\Entity\Scene;
use AppBundle\Form\Location\LocationType;
use AppBundle\Pagination\LocationPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Piotr Szymaszek
 */
class LocationController
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

    public function newAction(Request $request, Scene $scene)
    {
        $form = $this->getForm(LocationType::class, null, [
            'action' => $this->router->generate('app_location_new', [
                'id' => $scene->getId()
            ])
        ]);
        $result = true;
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $scene->addLocation($data);
            $this->manager->persist($data);
            $this->manager->flush();
        } elseif ($form->isSubmitted()) {
            $result = false;
        }

        return new JsonResponse(
            [
                'form' => $this->templating->render(
                    'partial\simpleForm.html.twig',
                    [
                        'form' => $form->createView(),
                        'scene' => $scene,
                        'h2Title' => 'location.header.new'
                    ]
                )
            ],
            $result ? 200 : 400
        );
    }

    public function editAction(Request $request, Location $location)
    {
        $form = $this->getForm(LocationType::class, $location, [
            'action' => $this->router->generate('app_location_edit', [
                'id' => $location->getId()
            ])
        ]);
        $result = true;
        $form->handleRequest($request);
        if ($form->isValid()) {
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

    public function listAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function deleteAction(Scene $scene, Location $location, $page)
    {
        $this->manager->remove($location);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    public function displayAction(Location $location)
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\locations\display.html.twig',
                ['location' => $location]
            )
        ]);
    }

    public function relatedAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\relatedList.html.twig',
                [
                    'locations' => $this->pagination->getRelated($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function addToSceneAction(Scene $scene, Location $location)
    {
        $scene->addLocation($location);
        $this->manager->flush();
        return new JsonResponse(['list' => $this->renderForSceneList($scene, 1)]);
    }

    /**
     * @param Scene $scene
     * @param type $page
     * @return string
     */
    private function renderForSceneList(Scene $scene, $page) : string
    {
        return $this->templating->render(
            'scene\locations\list.html.twig',
            [
                'locations' => $this->pagination->getForScene($scene, $page),
                'scene' => $scene
            ]
        );
    }

    /**
     * @param string $class
     * @return FormInterface
     */
    private function getForm($class, $data = null, $options = [])
    {
        return $this->formFactory->create($class, $data, $options);
    }

    /**
     * @return LocationRepository
     */
    private function getRepository()
    {
        return $this->manager->getRepository(Location::class);
    }
}
