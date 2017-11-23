<?php

declare(strict_types=1);

namespace Domain\Item\RemoveFromScene;

use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Bus\Messages\RemovedFromSceneSuccessMessage;
use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

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

    public function perform(): void
    {
        $this->scene->removeItem($this->item);
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
