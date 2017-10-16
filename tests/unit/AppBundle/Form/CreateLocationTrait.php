<?php

namespace Tests\AppBundle\Form;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;
use Domain\Location\Create;
use Ramsey\Uuid\Uuid;

trait CreateLocationTrait
{
    private function getLocation(?Scene $scene = null) : Location
    {
        $createDto = new Create\DTO($scene ?? $this->getScene());
        $createDto->setName('Miejsce');
        $location = new Location(Uuid::uuid4(), $createDto, $this->tester->getUser());
        $location->setLocale('pl');

        return $location;
    }
}
