<?php

declare(strict_types=1);

namespace App\Tests\JSON;

use App\JSON\EventParser;
use App\Repository\CharacterRepository;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use Domain\Entity\Character;
use Domain\Entity\Event;
use Domain\Entity\Location;
use Domain\Event\Meeting;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\PropertyAccess\PropertyAccess;

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

        $root = $this->prophesize(Character::class)->reveal();
        $relation = $this->prophesize(Character::class)->reveal();
        $location = $this->prophesize(Location::class)->reveal();
        $this->characterRepository->find('1')->shouldBeCalled()->willReturn($root);
        $this->characterRepository->find('2')->shouldBeCalled()->willReturn($relation);
        $this->locationRepository->find('1')->shouldBeCalled()->willReturn($location);

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
