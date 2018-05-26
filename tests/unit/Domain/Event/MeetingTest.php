<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Domain\Entity\Character;
use Domain\Entity\Location;
use Domain\Entity\User;
use Domain\Event\Meeting;
use PHPUnit\Framework\TestCase;

class MeetingTest extends TestCase
{
    public function testUserAccessWhenRootIsAnotherUsers()
    {
        $user = $this->createMock(User::class);

        $character1 = $this->createMock(Character::class);
        $character1->expects($this->exactly(3))->method('getCreatedBy')->willReturn($user);

        $character2 = $this->createMock(Character::class);
        $character2->expects($this->exactly(2))->method('getCreatedBy')->willReturn($user);

        $location = $this->createMock(Location::class);
        $location->expects($this->exactly(1))->method('getCreatedBy')->willReturn($user);

        $model = new Meeting();
        $model->setRoot($character1);
        $model->setRelation($character2);
        $model->setLocation($location);

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }
}
