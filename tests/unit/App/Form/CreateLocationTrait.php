<?php

declare(strict_types=1);

namespace App\Tests\Form;

use Domain\Location;
use Domain\Scene;
use Ramsey\Uuid\Uuid;

trait CreateLocationTrait
{
    private function getLocation(?Scene $scene = null) : Location
    {
        $location = new Location(
            Uuid::uuid4(),
            $scene ?? $this->getScene(),
            'Miejsce',
            null,
            null,
            $this->tester->getUser()
        );
        $location->setLocale('pl');

        return $location;
    }
}
