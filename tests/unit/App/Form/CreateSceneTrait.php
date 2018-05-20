<?php

declare(strict_types=1);

namespace App\Tests\Form;

use Domain\Entity\Scene;
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
