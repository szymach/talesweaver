<?php

declare(strict_types=1);

namespace AppBundle\Bus\Messages;

interface MessageCommandInterface
{
    public function getMessage(): Message;
}
