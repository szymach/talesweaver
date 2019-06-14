<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\RemoveFromScene;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Messages\Scene\RemovedFromSceneSuccessMessage;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;

final class Command implements AuthorAccessInterface, MessageCommandInterface
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

    public function isAllowed(Author $author): bool
    {
        return $this->scene->getCreatedBy() === $this->character->getCreatedBy()
            && $author === $this->character->getCreatedBy()
        ;
    }

    public function getMessage(): Message
    {
        return new RemovedFromSceneSuccessMessage(
            'character',
            ['%title%' => $this->character->getName(), '%sceneTitle%' => $this->scene->getTitle()]
        );
    }

    public function isMuted(): bool
    {
        return false;
    }
}
