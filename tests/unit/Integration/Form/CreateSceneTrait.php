<?php

declare(strict_types=1);

namespace Integration\Tests\Form;

use Domain\Scene;
use Ramsey\Uuid\Uuid;

trait CreateSceneTrait
{
    private function getScene() : Scene
    {
        $scene = new Scene(Uuid::uuid4(), 'Scena', null, $this->tester->getUser());
        $scene->setLocale('pl');

        return $scene;
    }
}
