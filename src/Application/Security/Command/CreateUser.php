<?php

namespace Talesweaver\Application\Security\Command;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;

class CreateUser implements MessageCommandInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.registration.alert.success',
            [],
            'success'
        );
    }
}
