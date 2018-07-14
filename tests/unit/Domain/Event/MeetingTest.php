<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use PHPUnit\Framework\TestCase;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;

class MeetingTest extends TestCase
{
    public function testAccessDeniedToUserWhenNoFieldsSet()
    {
        $model = new Meeting();
        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }

    public function testAccessDeniedToUserWhenOnlyRootSet()
    {
        $model = new Meeting();
        $model->setRoot($this->createMock(Character::class));

        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }

    public function testAccessDeniedToUserWhenOnlyRelationSet()
    {
        $model = new Meeting();
        $model->setRelation($this->createMock(Character::class));

        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }

    public function testAccessDeniedToUserWhenOnlyLocationSet()
    {
        $model = new Meeting();
        $model->setLocation($this->createMock(Location::class));

        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }

    public function testAccessDeniedToUserWhenRootAndLocationSet()
    {
        $author = $this->createMock(Author::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($author);
        $location = $this->createMock(Location::class);
        $location->expects($this->exactly(2))->method('getCreatedBy')->willReturn($author);

        $model = new Meeting();
        $model->setRoot($root);
        $model->setLocation($location);

        $this->assertFalse($model->isAllowed($author));
    }

    public function testAccessDeniedToUserWhenRootAndRelationSet()
    {
        $author = $this->createMock(Author::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($author);
        $relation = $this->createMock(Character::class);
        $relation->expects($this->exactly(1))->method('getCreatedBy')->willReturn($author);

        $model = new Meeting();
        $model->setRoot($root);
        $model->setRelation($relation);

        $this->assertFalse($model->isAllowed($author));
    }

    public function testAccessDeniedToUserWhenRelationAndLocationSet()
    {
        $author = $this->createMock(Author::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($author);
        $location = $this->createMock(Location::class);
        $location->expects($this->exactly(2))->method('getCreatedBy')->willReturn($author);

        $model = new Meeting();
        $model->setRoot($root);
        $model->setLocation($location);

        $this->assertFalse($model->isAllowed($author));
    }

    public function testUserAccessDeniedWhenRootIsAnotherUsers()
    {
        $author = $this->createMock(Author::class);

        $character1 = $this->createMock(Character::class);
        $character1->expects($this->exactly(4))->method('getCreatedBy')->willReturn($author);

        $character2 = $this->createMock(Character::class);
        $character2->expects($this->exactly(2))->method('getCreatedBy')->willReturn($author);

        $location = $this->createMock(Location::class);
        $location->expects($this->once())->method('getCreatedBy')->willReturn($author);

        $model = new Meeting();
        $model->setRoot($character1);
        $model->setRelation($character2);
        $model->setLocation($location);

        $this->assertFalse($model->isAllowed($this->createMock(Author::class)));
    }
}
