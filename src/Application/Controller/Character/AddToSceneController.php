<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\AddToScene\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

class AddToSceneController
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
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function __invoke(Scene $scene, Character $character): ResponseInterface
    {
        $this->commandBus->dispatch(new Command($scene, $character));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
