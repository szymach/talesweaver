<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Location;
use AppBundle\Entity\Repository\LocationRepository;
use AppBundle\Entity\Scene;
use AppBundle\Form\Location\LocationType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function newAction(Request $request, Scene $scene)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->getForm(LocationType::class, null, [
            'action' => $this->router->generate('app_scene_location_new', [
                'id' => $scene->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $scene->addLocation($data);
            $this->manager->persist($data);
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'scene\locations\form.html.twig',
                ['form' => $form->createView(), 'scene' => $scene]
            )
        ]);
    }

    public function editAction(Request $request, Location $location)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->getForm(LocationType::class, $location, [
            'action' => $this->router->generate('app_scene_location_edit', [
                'id' => $location->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'scene\locations\form.html.twig',
                ['form' => $form->createView()]
            )
        ]);
    }

    public function listAction(Request $request, Scene $scene)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->getRepository()->getForScene($scene),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function deleteAction(Request $request, Scene $scene, Location $location)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $this->manager->remove($location);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->getRepository()->getForScene($scene),
                    'scene' => $scene
                ]
            )
        ]);
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
