<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Form\Scene\EditType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
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

    public function editAction(Request $request, Scene $scene)
    {
        $form = $this->formFactory->create(EditType::class, $scene);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'scene/form.html.twig',
            ['form' => $form->createView()]
        );
    }
}
