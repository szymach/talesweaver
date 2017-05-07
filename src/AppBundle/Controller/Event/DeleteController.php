<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
use AppBundle\Enum\SceneEvents;
use AppBundle\Pagination\EventPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @var EventPaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        ObjectManager $manager,
        EventPaginator $pagination
    ) {
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * @ParamConverter("event", options={"id" = "event_id"})
     */
    public function deleteAction(Event $event, $page)
    {
        $scene = $event->getScene();
        $this->manager->remove($event);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\events\list.html.twig',
                [
                    'events' => $this->pagination->getForScene($scene, $page),
                    'eventModels' => SceneEvents::getAllEvents(),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
