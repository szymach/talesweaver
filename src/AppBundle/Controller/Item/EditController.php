<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use AppBundle\Form\Item\ItemType;
use AppBundle\Pagination\ItemPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function editAction(Request $request, Item $item)
    {
        $form = $this->formFactory->create(
            ItemType::class,
            $item,
            ['action' => $this->router->generate('app_item_edit', ['id' => $item->getId()])
        ]);

        $result = true;
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
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

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function addToSceneAction(Scene $scene, Item $item)
    {
        $scene->addItem($item);
        $this->manager->flush();
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getForScene($scene, 1),
                    'scene' => $scene
                ]
            )
        ]);
    }

}
