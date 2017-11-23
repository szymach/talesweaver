<?php

declare(strict_types=1);

namespace Domain\Character\AddToScene;

use AppBundle\Bus\Messages\AddedToSceneSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Character
     */
    private $character;

    public function __construct(Scene $scene, Character $character)
    {
        $this->scene = $scene;
        $this->character = $character;
    }

    public function perform(): void
    {
        $this->scene->addCharacter($this->character);
    }

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->character->getCreatedBy()->getId()
            && $user->getId() === $this->character->getCreatedBy()->getId()
        ;
    }

    public function getMessage(): Message
    {
        return new AddedToSceneSuccessMessage(
            'character',
            ['%title%' => $this->character->getName(), '%sceneTitle%' => $this->scene->getTitle()]
        );
    }
}
