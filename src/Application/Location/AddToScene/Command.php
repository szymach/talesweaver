<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\AddToScene;

use Talesweaver\Application\Messages\AddedToSceneSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Location
     */
    private $location;

    public function __construct(Scene $scene, Location $location)
    {
        $this->scene = $scene;
        $this->location = $location;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function isAllowed(Author $author): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->location->getCreatedBy()->getId()
            && $author->getId() === $this->location->getCreatedBy()->getId()
        ;
    }

    public function getMessage(): Message
    {
        return new AddedToSceneSuccessMessage(
            'location',
            ['%title%' => $this->location->getName(), '%sceneTitle%' => $this->scene->getTitle()]
        );
    }
}
