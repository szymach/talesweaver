<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Item;
use AppBundle\Entity\Repository\ItemRepository;
use AppBundle\Entity\Scene;
use AppBundle\Form\Item\ItemType;
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
class ItemController
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

        $form = $this->getForm(ItemType::class, null, [
            'action' => $this->router->generate('app_scene_item_new', [
                'id' => $scene->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $scene->addItem($data);
            $this->manager->persist($data);
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'scene\items\form.html.twig',
                ['form' => $form->createView(), 'scene' => $scene]
            )
        ]);
    }

    public function editAction(Request $request, Item $item)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->getForm(ItemType::class, $item, [
            'action' => $this->router->generate('app_scene_item_edit', [
                'id' => $item->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'scene\items\form.html.twig',
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
                'scene\items\list.html.twig',
                [
                    'items' => $this->getRepository()->getForScene($scene),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function deleteAction(Request $request, Scene $scene, Item $item)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $this->manager->remove($item);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->getRepository()->getForScene($scene),
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
     * @return ItemRepository
     */
    private function getRepository()
    {
        return $this->manager->getRepository(Item::class);
    }
}
