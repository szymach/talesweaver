<?php

namespace AppBundle\Controller\Character;

use Domain\Character\AddToScene\Command;
use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
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
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function __invoke(Scene $scene, Character $character)
    {
        $this->commandBus->handle(new Command($scene, $character));

        return new JsonResponse(['success' => true]);
    }
}
