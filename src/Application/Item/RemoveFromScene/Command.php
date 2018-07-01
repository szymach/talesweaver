<?php

declare(strict_types=1);

namespace Application\Item\RemoveFromScene;

use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Application\Messages\RemovedFromSceneSuccessMessage;
use Domain\Item;
use Domain\Scene;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var Item
     */
    private $item;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene, Item $item)
    {
        $this->scene = $scene;
        $this->item = $item;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->item->getCreatedBy()->getId()
            && $user->getId() === $this->item->getCreatedBy()->getId()
        ;
    }

    public function getMessage(): Message
    {
        return new RemovedFromSceneSuccessMessage(
            'item',
            ['%title%' => $this->item->getName(), '%sceneTitle%' => $this->scene->getTitle()]
        );
    }
}
