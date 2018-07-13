<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form;

use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Scene;
use UnitTester;

/**
 * @property UnitTester $tester
 */
trait CreateSceneTrait
{
    private function getScene(): Scene
    {
        $scene = new Scene(Uuid::uuid4(), 'Scena', null, $this->tester->getUser()->getAuthor());
        $scene->setLocale('pl');

        return $scene;
    }
}
