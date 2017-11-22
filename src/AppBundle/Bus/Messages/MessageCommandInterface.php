<?php

declare(strict_types=1);

namespace AppBundle\Bus\Messages;

interface MessageCommandInterface
{
    public function hasMessage(): bool;

    public function getMessage(): Message;
}
