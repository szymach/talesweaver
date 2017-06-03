<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Character;
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

    public function __invoke(Character $character)
    {
        $this->manager->remove($character);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
