<?php

declare(strict_types=1);

namespace App\Controller\Location;

use Domain\Entity\Location;
use Domain\Entity\Scene;
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
