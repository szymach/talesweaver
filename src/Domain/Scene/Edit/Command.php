<?php

declare(strict_types=1);

namespace Domain\Scene\Edit;

use AppBundle\Bus\Messages\EditionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
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

    public function perform(): void
    {
        $this->scene->edit($this->dto);
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
