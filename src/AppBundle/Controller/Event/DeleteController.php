<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
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

    public function __invoke(Event $event)
    {
        $this->manager->remove($event);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
