<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\JSON;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\User;
use Talesweaver\Integration\JSON\EventParser;
use Talesweaver\Integration\Repository\CharacterRepository;
use Talesweaver\Integration\Repository\ItemRepository;
use Talesweaver\Integration\Repository\LocationRepository;

class JSONParserTest extends TestCase
{
    /**
     * @var CharacterRepository|ObjectProphecy
     */
    private $characterRepository;

    /**
     * @var LocationRepository|ObjectProphecy
     */
    private $locationRepository;

    public function testEmptyModel()
    {
        $event = $this->createEvent([
            Meeting::class => ['root' => null, 'location' => null, 'relation' => null]
        ]);

        /* @var $parsedModel Meeting */
        $parsedModel = $this->createParser()->parse($event);
        $this->assertInstanceOf(Meeting::class, $parsedModel);
        $this->assertNull($parsedModel->getRoot());
        $this->assertNull($parsedModel->getRelation());
        $this->assertNull($parsedModel->getLocation());
    }

    public function testFullModel()
    {
        $event = $this->createEvent([
            Meeting::class => [
                'root' => [Character::class => '1'],
                'location' => [Location::class => '1'],
                'relation' => [Character::class => '2']
            ]
        ]);

        $author = $root = $this->prophesize(User::class);
        /* @var $root Character */
        $root = $this->prophesize(Character::class);
        $root->getCreatedBy()->shouldBeCalled()->willReturn($author);
        /* @var $relation Character */
        $relation = $this->prophesize(Character::class);
        $relation->getCreatedBy()->shouldBeCalled()->willReturn($author);
        /* @var $location Location */
        $location = $this->prophesize(Location::class);
        $location->getCreatedBy()->shouldBeCalled()->willReturn($author);
        $this->characterRepository->find('1')->shouldBeCalled()->willReturn($root->reveal());
        $this->characterRepository->find('2')->shouldBeCalled()->willReturn($relation->reveal());
        $this->locationRepository->find('1')->shouldBeCalled()->willReturn($location->reveal());

        /* @var $parsedModel Meeting */
        $parsedModel = $this->createParser()->parse($event);
        $this->assertInstanceOf(Meeting::class, $parsedModel);
        $this->assertInstanceOf(Character::class, $parsedModel->getRoot());
        $this->assertInstanceOf(Character::class, $parsedModel->getRelation());
        $this->assertInstanceOf(Location::class, $parsedModel->getLocation());
    }

    protected function setUp()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->characterRepository = $this->prophesize(CharacterRepository::class);
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->locationRepository = $this->prophesize(LocationRepository::class);
    }

    private function createEvent(array $eventData): Event
    {
        /* @var $event Event */
        $event = $this->prophesize(Event::class);
        $event->getModel()->shouldBeCalled()->willReturn($eventData);

        return $event->reveal();
    }

    private function createParser(): EventParser
    {
        return new EventParser(
            $this->propertyAccessor,
            $this->characterRepository->reveal(),
            $this->itemRepository->reveal(),
            $this->locationRepository->reveal()
        );
    }
}
