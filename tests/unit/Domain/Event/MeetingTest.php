<?php

declare(strict_types=1);

namespace Domain\Tests;

use Domain\Character;
use Domain\Location;
use Domain\User;
use Domain\Event\Meeting;
use PHPUnit\Framework\TestCase;

class MeetingTest extends TestCase
{
    public function testAccessDeniedToUserWhenNoFieldsSet()
    {
        $model = new Meeting();

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testAccessDeniedToUserWhenOnlyRootSet()
    {
        $model = new Meeting();
        $model->setRoot($this->createMock(Character::class));

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testAccessDeniedToUserWhenOnlyRelationSet()
    {
        $model = new Meeting();
        $model->setRelation($this->createMock(Character::class));

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testAccessDeniedToUserWhenOnlyLocationSet()
    {
        $model = new Meeting();
        $model->setLocation($this->createMock(Location::class));

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testAccessDeniedToUserWhenRootAndLocationSet()
    {
        $user = $this->createMock(User::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($user);
        $location = $this->createMock(Location::class);
        $location->expects($this->exactly(1))->method('getCreatedBy')->willReturn($user);

        $model = new Meeting();
        $model->setRoot($root);
        $model->setLocation($location);

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testAccessDeniedToUserWhenRootAndRelationSet()
    {
        $user = $this->createMock(User::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($user);
        $relation = $this->createMock(Character::class);
        $relation->expects($this->exactly(1))->method('getCreatedBy')->willReturn($user);

        $model = new Meeting();
        $model->setRoot($root);
        $model->setRelation($relation);

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testAccessDeniedToUserWhenRelationAndLocationSet()
    {
        $user = $this->createMock(User::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($user);
        $location = $this->createMock(Location::class);
        $location->expects($this->exactly(1))->method('getCreatedBy')->willReturn($user);

        $model = new Meeting();
        $model->setRoot($root);
        $model->setLocation($location);

        $this->assertFalse($model->isAllowed($this->createMock(User::class)));
    }

    public function testUserAccessDeniedWhenRootIsAnotherUsers()
    {
        $user = $this->createMock(User::class);

        $character1 = $this->createMock(Character::class);
        $character1->expects($this->exactly(4))->method('getCreatedBy')->willReturn($user);

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
