<?php

namespace App\Tests\Form;

use Domain\Entity\Character;
use Domain\Entity\Scene;
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
