<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Publish;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var bool
     */
    private $visible;

    /**
     * @param Scene $scene
     * @param ShortText $title
     * @param bool $visible
     */
    public function __construct(Scene $scene, ShortText $title, bool $visible)
    {
        $this->scene = $scene;
        $this->title = $title;
        $this->visible = $visible;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->scene->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new Message('scene.alert.published', ['%title%' => $this->scene->getTitle()], Message::SUCCESS);
    }

    public function isMuted(): bool
    {
        return false;
    }
}
