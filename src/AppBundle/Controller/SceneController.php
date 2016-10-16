<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\NewType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

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

    public function listAction($page = 1)
    {
        return $this->templating->renderResponse(
            'scene\list.html.twig',
            ['scenes' => $this->getScenes($page)]
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
     * @return Scene[]
     */
    private function getScenes($page)
    {
        $qb = $this->manager->getRepository('AppBundle:Scene')->createPaginatedQb($page);
        return new Paginator($qb);
    }
}
