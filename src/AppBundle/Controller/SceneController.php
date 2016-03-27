<?php

namespace AppBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\NewType;

/**
 * @author Piotr Szymaszek
 */
class SceneController
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

    public function newAction(Request $request)
    {
        $form = $this->getForm(NewType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            if (null === $data->getId()) {
                $this->manager->persist($data);
            }
            $this->manager->flush();
            
            return new RedirectResponse(
                $this->router->generate('app_scene_edit', ['id' => $data->getId()])
            );
        }

        return $this->templating->renderResponse(
            'scene\form.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function editAction(Request $request, Scene $scene)
    {
        $form = $this->getForm(NewType::class, $scene);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            if (null === $data->getId()) {
                $this->manager->persist($data);
            }
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'scene\form.html.twig',
            ['form' => $form->createView()]
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
}
