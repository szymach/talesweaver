<?php

namespace App\Tests\Form;

use App\Entity\Character;
use App\Entity\Scene;
use Domain\Character\Create;
use Ramsey\Uuid\Uuid;

trait CreateCharacterTrait
{
    private function getCharacter(?Scene $scene = null) : Character
    {
        $createDto = new Create\DTO($scene ?? $this->getScene());
        $createDto->setName('Postać');
        $character = new Character(Uuid::uuid4(), $createDto, $this->tester->getUser());
        $character->setLocale('pl');

        return $character;
    }
}
