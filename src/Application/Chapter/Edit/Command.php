<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var DTO
     */
    private $data;

    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct(DTO $data, Chapter $chapter)
    {
        $this->data = $data;
        $this->chapter = $chapter;
    }

    public function getData(): DTO
    {
        return $this->data;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function isAllowed(Author $author): bool
    {
        return $author->getId() === $this->chapter->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('chapter');
    }
}
