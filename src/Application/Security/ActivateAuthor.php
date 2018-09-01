<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

use DomainException;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;

class ActivateAuthor implements MessageCommandInterface
{
    /**
     * @var Author
     */
    private $author;

    public function __construct(Author $author)
    {
        if (true === $author->isActive()) {
            throw new DomainException(
                sprintf('Author "%s" is already active!', $author->getId()->toString())
            );
        }

        $this->author = $author;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.activation.alert.success',
            ['%email%' => (string) $this->author->getEmail()],
            'success'
        );
    }
}
