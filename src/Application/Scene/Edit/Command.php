<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Entity\User;

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
        return $user->getAuthor()->getId() === $this->scene->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('scene');
    }
}
