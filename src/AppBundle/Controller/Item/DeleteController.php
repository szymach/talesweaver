<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteController
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function deleteAction(Item $item)
    {
        $this->manager->remove($item);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function removeFromSceneAction(Scene $scene, Item $item)
    {
        $scene->removeItem($item);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
