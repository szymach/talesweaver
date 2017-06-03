<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Item;
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

    public function __invoke(Item $item)
    {
        $this->manager->remove($item);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
