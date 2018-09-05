<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Item\AddToScene\Command;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;

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
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function __invoke(Scene $scene, Item $item): ResponseInterface
    {
        $this->commandBus->handle(new Command($scene, $item));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
