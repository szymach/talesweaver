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
use Talesweaver\Application\Query\Character\ForScene;
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

    public function seeCharacterDoesNotExist(string $name, Scene $scene): void
    {
        $this->assertNull(
            $this->findCharacterInSceneForName($name, $scene),
            "Character for scene \"{$scene->getTitle()}\" and name \"{$name}\" should not exist."
        );
    }

    public function seeCharacterExists(string $name, Scene $scene): void
    {
        $this->assertNotNull(
            $this->findCharacterInSceneForName($name, $scene),
            "Character for scene \"{$scene->getTitle()}\" and name \"{$name}\" should exist."
        );
    }

    private function findCharacterInSceneForName(string $name, Scene $scene): ?Character
    {
        return array_reduce(
            $this->queryBus->query(new ForScene($scene)),
            function (?Character $accumulator, Character $character) use ($name): ?Character {
                if (null !== $accumulator) {
                    return $accumulator;
                }

                if ($name === (string) $character->getName()) {
                    $accumulator = $character;
                }

                return $accumulator;
            }
        );
    }
}
