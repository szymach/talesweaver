<?php

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\ShortText;

final class CreateAuthor implements MessageCommandInterface
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var ShortText|null
     */
    private $name;

    /**
     * @var ShortText|null
     */
    private $surname;

    public function __construct(Email $email, string $password, ?ShortText $name, ?ShortText $surname)
    {
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
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

    public function getName(): ?ShortText
    {
        return $this->name;
    }

    public function getSurname(): ?ShortText
    {
        return $this->surname;
    }

    public function getMessage(): Message
    {
        return new Message('security.registration.alert.success', [], 'success');
    }

    public function isMuted(): bool
    {
        return false;
    }
}
