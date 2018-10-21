<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Item\Create\Command;
use Talesweaver\Application\Query\Item\ById;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class ItemModule extends Module
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

    public function haveCreatedAnItem(string $name, Scene $scene): Item
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(new Command($scene, $id, new ShortText($name), null, null));

        $item = $this->queryBus->query(new ById($id));
        $this->assertInstanceOf(Item::class, $item);

        return $item;
    }
}
