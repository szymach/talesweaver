<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Application\Character\RemoveFromScene\Command;
use Talesweaver\Domain\Character;
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
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function __invoke(Scene $scene, Character $character)
    {
        $this->commandBus->handle(new Command($scene, $character));

        return new JsonResponse(['success' => true]);
    }
}
