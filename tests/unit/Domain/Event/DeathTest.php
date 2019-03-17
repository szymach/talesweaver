<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use PHPUnit\Framework\TestCase;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event\Death;
use Talesweaver\Domain\Location;

final class DeathTest extends TestCase
{
    public function testAccessDeniedToUserWhenNoFieldsSet()
    {
        $model = new Death();
        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }

    public function testAccessDeniedToUserWhenOnlycharacterSet()
    {
        $model = new Death();
        $model->setCharacter($this->createMock(Character::class));

        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }

    public function testAccessDeniedToUserWhenOnlyLocationSet()
    {
        $model = new Death();
        $model->setLocation($this->createMock(Location::class));

        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }
}
