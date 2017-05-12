<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\NewType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function createAction(Request $request)
    {
        $scene = new Scene();
        $form = $this->formFactory->create(NewType::class, $scene);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->persist($scene);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_scene_edit', ['id' => $scene->getId()])
            );
        }

        return $this->templating->renderResponse(
            'scene/form.html.twig',
            ['form' => $form->createView()]
        );
    }
}
