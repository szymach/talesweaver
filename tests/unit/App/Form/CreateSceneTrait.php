<?php

namespace App\Tests\Form;

use App\Entity\Scene;
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
