<?php

namespace AppBundle\Character\Create;

use AppBundle\Entity\Character;

class CommandHandler
{
    public function handle(Command $command)
    {
        $this->manager->persist(new Character($command->getId(), $command->getData()));
    }
}
