<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security\Command;

class ActivateUserHandler
{
    public function handle(ActivateUser $user)
    {
        $user->getUser()->activate();
    }
}
