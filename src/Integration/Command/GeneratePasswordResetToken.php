<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Command;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;

class GeneratePasswordResetToken implements MessageCommandInterface
{
    /**
     * @var string
     */
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.reset_password.request.alert.success',
            [],
            'success'
        );
    }
}
