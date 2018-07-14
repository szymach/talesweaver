<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Bus\Command;

class ActivateUserHandler
{
    public function handle(ActivateUser $user)
    {
        $user->getUser()->activate();
    }
}
