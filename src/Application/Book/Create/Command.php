<?php

declare(strict_types=1);

namespace Application\Book\Create;

use Application\Messages\CreationSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Application\Security\Traits\UserAwareTrait;
use Application\Security\UserAwareInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements MessageCommandInterface, UserAwareInterface
{
    use UserAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    public function __construct(UuidInterface $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('book', ['%title%' => $this->title]);
    }
}
