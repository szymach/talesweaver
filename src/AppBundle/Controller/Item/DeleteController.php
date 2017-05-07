<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
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

    public function __construct(EngineInterface $templating, ObjectManager $manager)
    {
        $this->templating = $templating;
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
