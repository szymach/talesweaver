<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
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

    public function deleteAction(Location $location)
    {
        $this->manager->remove($location);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function removeFromSceneAction(Scene $scene, Location $location)
    {
        $scene->removeLocation($location);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
