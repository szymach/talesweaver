<?php

namespace App\Controller\Location;

use App\Entity\Location;
use App\Entity\Scene;
use Domain\Location\AddToScene\Command;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddToSceneController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    public function __construct(MessageBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function __invoke(Scene $scene, Location $location)
    {
        $this->commandBus->handle(new Command($scene, $location));

        return new JsonResponse(['success' => true]);
    }
}
