<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Scene\Create\Command;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\Query\Scene\ByTitle;

final class SceneModule extends Module
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

    public function haveCreatedAScene(string $title, Chapter $chapter = null): Scene
    {
        $this->commandBus->dispatch(new Command(Uuid::uuid4(), new ShortText($title), $chapter));

        return $this->grabSceneByTitle($title);
    }

    public function grabSceneByTitle(string $title): Scene
    {
        $scene = $this->queryBus->query(new ByTitle(new ShortText($title)));
        $this->assertInstanceOf(Scene::class, $scene);

        return $scene;
    }

    public function seeSceneHasBeenRemoved(UuidInterface $id): void
    {
        $this->assertNull($this->queryBus->query(new ById($id)));
    }
}
