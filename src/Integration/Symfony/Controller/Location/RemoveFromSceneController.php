<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Location\RemoveFromScene\Command;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

class RemoveFromSceneController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactoryInterface $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function __invoke(Scene $scene, Location $location): ResponseInterface
    {
        $this->commandBus->dispatch(new Command($scene, $location));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
