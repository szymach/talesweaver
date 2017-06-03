<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;
use AppBundle\Form\Location\LocationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

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
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function __invoke(Request $request, Scene $scene)
    {
        $location = new Location();
        $form = $this->formFactory->create(
            LocationType::class,
            $location,
            ['action' => $this->router->generate('app_location_new', ['id' => $scene->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $scene->addLocation($location);
            $this->manager->persist($location);
            $this->manager->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'location.header.new']
            )
        ], !$form->isSubmitted() || $form->isValid()? 200 : 400);
    }
}
