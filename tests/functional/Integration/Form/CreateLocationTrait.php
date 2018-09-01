<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

trait CreateLocationTrait
{
    private function getLocation(FunctionalTester $I, ?Scene $scene = null): Location
    {
        $location = new Location(
            Uuid::uuid4(),
            $scene ?? $this->getScene($I),
            new ShortText('Miejsce'),
            null,
            null,
            $I->getAuthor()
        );
        $location->setLocale('pl');

        return $location;
    }
}
