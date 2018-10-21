<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Character\Create\Command;
use Talesweaver\Application\Query\Character\ById;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class CharacterModule extends Module
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

    public function haveCreatedACharacter(string $name, Scene $scene): Character
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(new Command($scene, $id, new ShortText($name), null, null));

        $character = $this->queryBus->query(new ById($id));
        $this->assertInstanceOf(Character::class, $character);

        return $character;
    }
}
