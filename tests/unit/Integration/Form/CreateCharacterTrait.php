<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;
use Ramsey\Uuid\Uuid;

trait CreateCharacterTrait
{
    private function getCharacter(?Scene $scene = null) : Character
    {
        $character = new Character(
            Uuid::uuid4(),
            $scene ?? $this->getScene(),
            'Postać',
            null,
            null,
            $this->tester->getUser()
        );
        $character->setLocale('pl');

        return $character;
    }
}
