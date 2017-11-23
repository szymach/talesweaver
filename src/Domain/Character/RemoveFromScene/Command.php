<?php

declare(strict_types=1);

namespace Domain\Character\RemoveFromScene;

use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Bus\Messages\RemovedFromSceneSuccessMessage;
use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
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
