<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\RemoveFromScene;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Messages\RemovedFromSceneSuccessMessage;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Entity\User;

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
