<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Location\AddToScene\Command;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

class AddToSceneController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(MessageBus $commandBus, ResponseFactoryInterface $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function __invoke(Scene $scene, Location $location): ResponseFactoryInterface
    {
        $this->commandBus->handle(new Command($scene, $location));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
