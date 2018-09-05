<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Location\RemoveFromScene\Command;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

class RemoveFromSceneController
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
    public function __invoke(Scene $scene, Location $location): ResponseInterface
    {
        $this->commandBus->handle(new Command($scene, $location));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
