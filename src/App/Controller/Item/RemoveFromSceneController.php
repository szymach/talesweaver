<?php

declare(strict_types=1);

namespace App\Controller\Item;

use Domain\Item;
use Domain\Scene;
use Application\Item\RemoveFromScene\Command;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @ParamConverter("item", options={"id" = "item_id"})
     */
    public function __invoke(Scene $scene, Item $item)
    {
        $this->commandBus->handle(new Command($scene, $item));

        return new JsonResponse(['success' => true]);
    }
}
