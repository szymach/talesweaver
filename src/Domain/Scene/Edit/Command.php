<?php

declare(strict_types=1);

namespace Domain\Scene\Edit;

use App\Bus\Messages\EditionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\Scene;
use Domain\Entity\User;
use Domain\Security\UserAccessInterface;

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
