<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function deleteAction(Event $event)
    {
        $this->manager->remove($event);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
