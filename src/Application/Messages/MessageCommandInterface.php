<?php

declare(strict_types=1);

namespace Application\Messages;

interface MessageCommandInterface
{
    public function getMessage(): Message;
}
