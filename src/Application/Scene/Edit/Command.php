<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, MessageCommandInterface
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

    public function __construct(Scene $scene, ShortText $title, ?LongText $text, ?Chapter $chapter)
    {
        $this->scene = $scene;
        $this->title = $title;
        $this->text = $text;
        $this->chapter = $chapter;
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
}
