<?php

declare(strict_types=1);

namespace Application\Character\RemoveFromScene;

use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Application\Messages\RemovedFromSceneSuccessMessage;
use Domain\Character;
use Domain\Scene;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var Character
     */
    private $character;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene, Character $character)
    {
        $this->scene = $scene;
        $this->character = $character;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->character->getCreatedBy()->getId()
            && $user->getId() === $this->character->getCreatedBy()->getId()
        ;
    }

    public function getMessage(): Message
    {
        return new RemovedFromSceneSuccessMessage(
            'character',
            ['%title%' => $this->character->getName(), '%sceneTitle%' => $this->scene->getTitle()]
        );
    }
}
