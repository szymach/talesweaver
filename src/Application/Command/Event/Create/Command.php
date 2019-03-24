<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Create;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Command\Security\Traits\AuthorAwareTrait;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, AuthorAwareInterface, MessageCommandInterface
{
    use AuthorAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText|null
     */
    private $description;

    /**
     * @var Character[]
     */
    private $characters;

    /**
     * @var Item[]
     */
    private $items;

    public function __construct(
        UuidInterface $id,
        Scene $scene,
        ShortText $name,
        ?LongText $description,
        array $characters,
        array $items
    ) {
        Assertion::allIsInstanceOf(
            $characters,
            Character::class,
            "Not all objects that were passed are characters (scene \"{$scene->getId()->toString()}\")."
        );
        Assertion::allIsInstanceOf(
            $items,
            Item::class,
            "Not all objects that were passed are items (scene \"{$scene->getId()->toString()}\")."
        );

        $this->id = $id;
        $this->scene = $scene;
        $this->name = $name;
        $this->description = $description;
        $this->characters = $characters;
        $this->items = $items;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function isAllowed(Author $author): bool
    {
        $charactersBelong = array_reduce(
            $this->characters,
            function (bool $accumulator, Character $character) use ($author): bool {
                if (false === $accumulator) {
                    return $accumulator;
                }

                return $author === $character->getCreatedBy();
            },
            true
        );

        $itemsBelong = array_reduce(
            $this->items,
            function (bool $accumulator, Item $item) use ($author): bool {
                if (false === $accumulator) {
                    return $accumulator;
                }

                return $author === $item->getCreatedBy();
            },
            true
        );

        return $author === $this->scene->getCreatedBy() && true === $charactersBelong && true === $itemsBelong;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('event', ['%title%' => $this->name]);
    }
}
