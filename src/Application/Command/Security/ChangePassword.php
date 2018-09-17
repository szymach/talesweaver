<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;

class ChangePassword implements MessageCommandInterface
{
    /**
     * @var Author
     */
    private $author;

    /**
     * @var string
     */
    private $newPassword;

    public function __construct(Author $author, string $newPassword)
    {
        $this->author = $author;
        $this->newPassword = $newPassword;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.change_password.alert.success',
            [],
            'success'
        );
    }
}
