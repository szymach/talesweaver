<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Command\Security\Traits\AuthorAwareTrait;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAwareInterface, MessageCommandInterface
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

    public function __construct(UuidInterface $id, ShortText $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('book', ['%title%' => $this->title]);
    }

    public function isMuted(): bool
    {
        return false;
    }
}
