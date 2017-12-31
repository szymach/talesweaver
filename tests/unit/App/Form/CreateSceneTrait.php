<?php

namespace App\Tests\Form;

use App\Entity\Scene;
use Domain\Scene\Create;
use Ramsey\Uuid\Uuid;

trait CreateSceneTrait
{
    private function getScene() : Scene
    {
        $createDto = new Create\DTO();
        $createDto->setTitle('Scena');
        $scene = new Scene(Uuid::uuid4(), $createDto, $this->tester->getUser());
        $scene->setLocale('pl');

        return $scene;
    }
}
