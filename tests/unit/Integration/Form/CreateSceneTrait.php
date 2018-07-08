<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;

trait CreateSceneTrait
{
    private function getScene(): Scene
    {
        $scene = new Scene(Uuid::uuid4(), 'Scena', null, $this->tester->getUser());
        $scene->setLocale('pl');

        return $scene;
    }
}
