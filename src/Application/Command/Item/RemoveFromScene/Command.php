<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\RemoveFromScene;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Messages\Scene\RemovedFromSceneSuccessMessage;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
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

    public function isAllowed(Author $author): bool
    {
        return $this->scene->getCreatedBy() === $this->item->getCreatedBy()
            && $author === $this->item->getCreatedBy()
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
