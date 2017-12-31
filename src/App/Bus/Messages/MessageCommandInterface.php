<?php

declare(strict_types=1);

namespace App\Bus\Messages;

interface MessageCommandInterface
{
    public function getMessage(): Message;
}
