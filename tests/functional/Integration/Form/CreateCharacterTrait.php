<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

trait CreateCharacterTrait
{
    private function getCharacter(FunctionalTester $I, ?Scene $scene = null): Character
    {
        $character = new Character(
            Uuid::uuid4(),
            $scene ?? $this->getScene($I),
            new ShortText('Postać'),
            null,
            null,
            $I->getUser()->getAuthor()
        );
        $character->setLocale('pl');

        return $character;
    }
}
