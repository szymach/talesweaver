<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use AppBundle\Pagination\ItemPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class DeleteController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ItemPaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        ObjectManager $manager,
        ItemPaginator $pagination
    ) {
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
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
}
