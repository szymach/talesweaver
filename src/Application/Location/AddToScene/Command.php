<?php

declare(strict_types=1);

namespace Application\Location\AddToScene;

use Application\Messages\AddedToSceneSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Location;
use Domain\Scene;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
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

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->location->getCreatedBy()->getId()
            && $user->getId() === $this->location->getCreatedBy()->getId()
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
