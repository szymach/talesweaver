<?php

declare(strict_types=1);

namespace Domain\Location\RemoveFromScene;

use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use App\Bus\Messages\RemovedFromSceneSuccessMessage;
use Domain\Entity\Location;
use Domain\Entity\Scene;
use Domain\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var Location
     */
    private $location;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene, Location $location)
    {
        $this->scene = $scene;
        $this->location = $location;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->location->getCreatedBy()->getId()
            && $user->getId() === $this->location->getCreatedBy()->getId()
        ;
    }

    public function getMessage(): Message
    {
        return new RemovedFromSceneSuccessMessage(
            'location',
            ['%title%' => $this->location->getName(), '%sceneTitle%' => $this->scene->getTitle()]
        );
    }
}
