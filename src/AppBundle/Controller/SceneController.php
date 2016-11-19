<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\NewType;
use AppBundle\Form\Scene\EditType;
use AppBundle\Pagination\Aggregate\SceneAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @ParamConverter("chapter", options={"id" = "chapter_id"})
     */
    public function newAction(Request $request, Chapter $chapter = null)
    {
        $scene = new Scene();
        if ($chapter) {
            $scene->setChapter($chapter);
        }
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
                'characters' => $this->pagination->getCharactersForScene($scene),
                'items' => $this->pagination->getItemsForScene($scene),
                'locations' => $this->pagination->getLocationsForScene($scene),
                'scene' => $scene
            ]
        );
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'scene/standalone/list.html.twig',
            ['scenes' => $this->pagination->getStandalone($page)]
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

    /**
     * @param string $class
     * @return FormInterface
     */
    private function getForm($class, $data = null, $options = [])
    {
        return $this->formFactory->create($class, $data, $options);
    }
}
