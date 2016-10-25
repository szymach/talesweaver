<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\NewType;
use AppBundle\Form\Scene\EditType;
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
class StandaloneController
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
        $scene = new Scene();
        $form = $this->getForm(NewType::class, $scene);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $this->manager->persist($data);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_standalone_scene_edit', ['id' => $data->getId()])
            );
        }

        return $this->templating->renderResponse(
            'scene/standalone/form.html.twig',
            ['form' => $form->createView(), 'scene' => $scene]
        );
    }

    public function editAction(Request $request, Scene $scene)
    {
        $form = $this->getForm(EditType::class, $scene);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'scene/standalone/form.html.twig',
            [
                'form' => $form->createView(),
                'characters' => $this->getCharacters($scene),
                'scene' => $scene
            ]
        );
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'scene/standalone/list.html.twig',
            ['scenes' => $this->getScenes($page)]
        );
    }

    public function deleteAction(Scene $scene, $page)
    {
        $this->manager->remove($scene);
        $this->manager->flush();

        return new RedirectResponse(
            $this->router->generate('app_standalone_scene_list', ['page' => $page])
        );
    }

    private function getCharacters(Scene $scene)
    {
        return $this->manager->getRepository(Character::class)->getForScene($scene);
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
        $qb = $this->manager->getRepository(Scene::class)->createPaginatedQb($page);
        return new Paginator($qb);
    }
}
