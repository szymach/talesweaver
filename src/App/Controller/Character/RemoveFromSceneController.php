<?php

declare(strict_types=1);

namespace App\Controller\Character;

use App\Entity\Character;
use App\Entity\Scene;
use Domain\Character\RemoveFromScene\Command;
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
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function __invoke(Scene $scene, Character $character)
    {
        $this->commandBus->handle(new Command($scene, $character));

        return new JsonResponse(['success' => true]);
    }
}
