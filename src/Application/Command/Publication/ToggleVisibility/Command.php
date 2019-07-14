<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Publication\ToggleVisibility;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Security\AuthorAccessInterface;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Publication
     */
    private $publication;

    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }

    public function getPublication(): Publication
    {
        return $this->publication;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->publication->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new Message(
            sprintf(
                'publication.alert.visibility_toggled.%s',
                $this->publication->isVisible() ? 'on' : 'off'
            ),
            ['%title%' => $this->publication->getTitle()],
            Message::SUCCESS
        );
    }

    public function isMuted(): bool
    {
        return false;
    }
}
