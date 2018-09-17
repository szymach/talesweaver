<?php

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\ValueObject\Email;

class CreateAuthor implements MessageCommandInterface
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    public function __construct(string $email, string $password)
    {
        $this->email = new Email($email);
        $this->password = $password;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getMessage(): Message
    {
        return new Message('security.registration.alert.success', [], 'success');
    }
}
