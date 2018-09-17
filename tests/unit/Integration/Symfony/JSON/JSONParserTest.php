<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\JSON;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Integration\Symfony\JSON\EventParser;
use Talesweaver\Integration\Symfony\Repository\CharacterRepository;
use Talesweaver\Integration\Symfony\Repository\ItemRepository;
use Talesweaver\Integration\Symfony\Repository\LocationRepository;

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
        $parsedModel = $this->createParser()->parse($event->getModel());
        $this->assertInstanceOf(Meeting::class, $parsedModel);
        $this->assertNull($parsedModel->getRoot());
        $this->assertNull($parsedModel->getRelation());
        $this->assertNull($parsedModel->getLocation());
    }

    public function testFullModel()
    {
        $event = $this->createEvent([
            Meeting::class => [
                'root' => [Character::class => 'f81b3a85-9150-4407-8d89-1edc1fabcbc1'],
                'location' => [Location::class => 'cffb5576-88ad-4053-8394-479af36a833f'],
                'relation' => [Character::class => '0e984272-0436-4f3c-ae10-228e9916be77']
            ]
        ]);

        $author = $this->prophesize(Author::class);
        /* @var $root Character */
        $root = $this->prophesize(Character::class);
        $root->getCreatedBy()->shouldBeCalled()->willReturn($author);
        /* @var $relation Character */
        $relation = $this->prophesize(Character::class);
        $relation->getCreatedBy()->shouldBeCalled()->willReturn($author);
        /* @var $location Location */
        $location = $this->prophesize(Location::class);
        $location->getCreatedBy()->shouldBeCalled()->willReturn($author);
        $this->characterRepository->find(Argument::that(function (UuidInterface $id): bool {
            return 'f81b3a85-9150-4407-8d89-1edc1fabcbc1' === $id->toString();
        }))->shouldBeCalled()->willReturn($root->reveal());
        $this->characterRepository->find(Argument::that(function (UuidInterface $id): bool {
            return '0e984272-0436-4f3c-ae10-228e9916be77' === $id->toString();
        }))->shouldBeCalled()->willReturn($relation->reveal());
        $this->locationRepository->find(Argument::type(UuidInterface::class))->shouldBeCalled()->willReturn($location->reveal());

        /* @var $parsedModel Meeting */
        $parsedModel = $this->createParser()->parse($event->getModel());
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
