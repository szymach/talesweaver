<?php

declare(strict_types=1);

namespace Application\Scene\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Scene;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(DTO $dto, Scene $scene)
    {
        $this->dto = $dto;
        $this->scene = $scene;
    }

    public function getDto(): DTO
    {
        return $this->dto;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $user->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('scene');
    }
}
