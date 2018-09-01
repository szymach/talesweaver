<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\FunctionalTester;

trait CreateSceneTrait
{
    private function getScene(FunctionalTester $I): Scene
    {
        $scene = new Scene(Uuid::uuid4(), new ShortText('Scena'), null, $I->getAuthor());
        $scene->setLocale('pl');

        return $scene;
    }
}
