<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\LongText;
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
     * @var LongText|null
     */
    private $text;

    /**
     * @var Chapter|null
     */
    private $chapter;

    /**
     * @var bool
     */
    private $muteMessage;

    public function __construct(Scene $scene, ShortText $title, ?LongText $text, ?Chapter $chapter, bool $muteMessage)
    {
        $this->scene = $scene;
        $this->title = $title;
        $this->text = $text;
        $this->chapter = $chapter;
        $this->muteMessage = $muteMessage;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getText(): ?LongText
    {
        return $this->text;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->scene->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('scene');
    }

    public function isMuted(): bool
    {
        return $this->muteMessage;
    }
}
