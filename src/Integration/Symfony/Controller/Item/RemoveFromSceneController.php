<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Command\Item\RemoveFromScene\Command;
use Talesweaver\Domain\Item;
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
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function __invoke(Scene $scene, Item $item)
    {
        $this->commandBus->dispatch(new Command($scene, $item));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
