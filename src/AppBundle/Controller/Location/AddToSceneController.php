<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddToSceneController
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function addToSceneAction(Scene $scene, Location $location)
    {
        $scene->addLocation($location);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }

}
