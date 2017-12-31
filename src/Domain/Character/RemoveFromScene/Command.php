<?php

declare(strict_types=1);

namespace Domain\Character\RemoveFromScene;

use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use App\Bus\Messages\RemovedFromSceneSuccessMessage;
use App\Entity\Character;
use App\Entity\Scene;
use App\Entity\User;
use Domain\Security\UserAccessInterface;

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

    public function perform(): void
    {
        $this->scene->removeCharacter($this->character);
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
