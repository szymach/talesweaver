<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\JSON;

use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Query;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Integration\Symfony\JSON\EventParser;

class EventParserTest extends TestCase
{
    public function testEmptyModel()
    {
        $event = $this->createEvent([
            Meeting::class => ['root' => null, 'location' => null, 'relation' => null]
        ]);

        $parser = new EventParser(PropertyAccess::createPropertyAccessor(), $this->createMock(QueryBus::class));
        $parsedModel = $parser->parse($event->getModel());
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

        $author = $this->createMock(Author::class);

        $root = $this->createMock(Character::class);
        $root->expects($this->exactly(3))->method('getCreatedBy')->willReturn($author);

        $relation = $this->createMock(Character::class);
        $relation->expects($this->once())->method('getCreatedBy')->willReturn($author);

        $location = $this->createMock(Location::class);
        $location->expects($this->exactly(2))->method('getCreatedBy')->willReturn($author);

        $queryBus = $this->createMock(QueryBus::class);
        $queryBus->expects($this->exactly(3))
            ->method('query')
            ->withConsecutive(
                $this->callback(
                    function (Query\Character\ById $command): bool {
                        return 'f81b3a85-9150-4407-8d89-1edc1fabcbc1' === $command->getId()->toString();
                    }
                ),
                $this->callback(
                    function (Query\Location\ById $command): bool {
                        return '0e984272-0436-4f3c-ae10-228e9916be77' === $command->getId()->toString();
                    }
                ),
                $this->callback(
                    function (Query\Character\ById $command): bool {
                        return '0e984272-0436-4f3c-ae10-228e9916be77' === $command->getId()->toString();
                    }
                )

            )
            ->willReturnOnConsecutiveCalls($root, $location, $relation)
        ;

        $parser = new EventParser(PropertyAccess::createPropertyAccessor(), $queryBus);
        /* @var $parsedModel Meeting */
        $parsedModel = $parser->parse($event->getModel());
        $this->assertInstanceOf(Meeting::class, $parsedModel);
        $this->assertInstanceOf(Character::class, $parsedModel->getRoot());
        $this->assertInstanceOf(Character::class, $parsedModel->getRelation());
        $this->assertInstanceOf(Location::class, $parsedModel->getLocation());
    }

    private function createEvent(array $eventData): Event
    {
        $event = $this->createMock(Event::class);
        $event->expects($this->once())->method('getModel')->willReturn($eventData);

        return $event;
    }
}
