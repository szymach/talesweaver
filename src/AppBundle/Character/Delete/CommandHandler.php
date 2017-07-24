<?php

namespace AppBundle\Character\Delete;

use AppBundle\Entity\Character;

class CommandHandler
{
    public function handle(Command $command)
    {
        $this->manager->remove(
            $this->manager->getRepository(Character::class)->find($command->getId())
        );
    }
}
