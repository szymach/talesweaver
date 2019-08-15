<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Delete;

use Talesweaver\Application\Messages\DeletionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene)
    {
        $this->scene = $scene;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->scene->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new DeletionSuccessMessage('scene', ['%title%' => $this->scene->getTitle()]);
    }

    public function isMuted(): bool
    {
        return false;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }
}
