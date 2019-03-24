<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Event\Create\Command;
use Talesweaver\Application\Query\Event\ById;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class EventModule extends Module
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * phpcs:disable
     */
    public function _before(TestInterface $test)
    {
        /* @var $container ContainerModule */
        $container = $this->getModule(ContainerModule::class);
        $this->commandBus = $container->getService(CommandBus::class);
        $this->queryBus = $container->getService(QueryBus::class);
    }

    public function haveCreatedAnEvent(
        string $name,
        Scene $scene,
        ?Location $location = null,
        array $characters = [],
        array $items = []
    ): Event {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(
            new Command($id, $scene, new ShortText($name), $location, null, $characters, $items)
        );

        $event = $this->queryBus->query(new ById($id));
        $this->assertInstanceOf(Event::class, $event);

        return $event;
    }
}
