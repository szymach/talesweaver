<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\ValueObject\Email;

class GeneratePasswordResetToken implements MessageCommandInterface
{
    /**
     * @var Email
     */
    private $email;

    public function __construct(string $email)
    {
        $this->email = new Email($email);
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getMessage(): Message
    {
        return new Message('security.reset_password.request.alert.success', [], 'success');
    }
}
