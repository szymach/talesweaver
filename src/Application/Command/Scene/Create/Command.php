<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Command\Security\Traits\AuthorAwareTrait;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements MessageCommandInterface, AuthorAwareInterface
{
    use AuthorAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(UuidInterface $id, ShortText $title, ?Chapter $chapter)
    {
        $this->id = $id;
        $this->title = $title;
        $this->chapter = $chapter;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('scene', ['%title%' => $this->title]);
    }

    public function isMuted(): bool
    {
        return false;
    }
}
