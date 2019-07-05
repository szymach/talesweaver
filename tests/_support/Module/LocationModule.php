<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Location\Create\Command;
use Talesweaver\Application\Query\Location\ById;
use Talesweaver\Application\Query\Location\ForScene;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class LocationModule extends Module
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

    public function haveCreatedALocation(string $name, Scene $scene): Location
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(new Command($scene, $id, new ShortText($name), null, null));

        $location = $this->queryBus->query(new ById($id));
        $this->assertInstanceOf(Location::class, $location);

        return $location;
    }

    public function seeLocationDoesNotExist(string $name, Scene $scene): void
    {
        $this->assertNull(
            $this->findLocationInSceneForName($name, $scene),
            "Location for scene \"{$scene->getTitle()}\" and name \"{$name}\" should not exist."
        );
    }

    public function seeLocationExists(string $name, Scene $scene): void
    {
        $this->assertNotNull(
            $this->findLocationInSceneForName($name, $scene),
            "Location for scene \"{$scene->getTitle()}\" and name \"{$name}\" should exist."
        );
    }

    private function findLocationInSceneForName(string $name, Scene $scene): ?Location
    {
        return array_reduce(
            $this->queryBus->query(new ForScene($scene)),
            function (?Location $accumulator, Location $location) use ($name): ?Location {
                if (null !== $accumulator) {
                    return $accumulator;
                }

                if ($name === (string) $location->getName()) {
                    $accumulator = $location;
                }

                return $accumulator;
            }
        );
    }
}
