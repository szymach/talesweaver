<?php

declare(strict_types=1);

namespace Talesweaver\Application\Messages;

interface MessageCommandInterface
{
    public function getMessage(): Message;
}
