<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

trait CreateLocationTrait
{
    private function getLocation(?Scene $scene = null): Location
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
