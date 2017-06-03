<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function __invoke(Location $location)
    {
        $this->manager->remove($location);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
