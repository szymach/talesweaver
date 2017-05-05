<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Repository\ItemRepository;
use AppBundle\Entity\Scene;
use AppBundle\Form\Item\ItemType;
use AppBundle\Pagination\ItemPaginator;
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
     * @var ItemPaginator
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
        ItemPaginator $pagination,
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
        $form = $this->getForm(ItemType::class, null, [
            'action' => $this->router->generate('app_item_new', [
                'id' => $scene->getId()
            ])
        ]);
        $result = true;
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $scene->addItem($data);
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
                        'h2Title' => 'item.header.new'
                    ]
                )
            ],
            $result ? 200 : 400
        );
    }

    public function editAction(Request $request, Item $item)
    {
        $form = $this->getForm(ItemType::class, $item, [
            'action' => $this->router->generate('app_item_edit', [
                'id' => $item->getId()
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
                    ['form' => $form->createView(), 'h2Title' => 'item.header.edit']
                )
            ],
            $result ? 200 : 400
        );
    }

    public function listAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene,
                    'page' => $page
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function deleteAction(Scene $scene, Item $item, $page)
    {
        $this->manager->remove($item);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function removeFromSceneAction(Scene $scene, Item $item, $page)
    {
        $scene->removeItem($item);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    public function displayAction(Item $item)
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\items\display.html.twig',
                ['item' => $item]
            )
        ]);
    }

    public function relatedAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\relatedList.html.twig',
                [
                    'items' => $this->pagination->getRelated($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function addToSceneAction(Scene $scene, Item $item)
    {
        $scene->addItem($item);
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
            'scene\items\list.html.twig',
            [
                'items' => $this->pagination->getForScene($scene, $page),
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
     * @return ItemRepository
     */
    private function getRepository()
    {
        return $this->manager->getRepository(Item::class);
    }
}
