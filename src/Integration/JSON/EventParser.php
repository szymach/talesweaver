<?php

declare(strict_types=1);

namespace Integration\JSON;

use Integration\Repository\CharacterRepository;
use Integration\Repository\ItemRepository;
use Integration\Repository\LocationRepository;
use Domain\Character;
use Domain\Event;
use Domain\Item;
use Domain\Location;
use JsonSerializable;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class EventParser
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertAccessor;

    /**
     * @var array
     */
    private $repositories;

    public function __construct(
        PropertyAccessorInterface $propertAccessor,
        CharacterRepository $characterRepository,
        ItemRepository $itemRepository,
        LocationRepository $locationRepository
    ) {
        $this->propertAccessor = $propertAccessor;
        $this->repositories = [
            Character::class => $characterRepository,
            Item::class => $itemRepository,
            Location::class => $locationRepository
        ];
    }

    public function parse(Event $event): JsonSerializable
    {
        $modelData = $event->getModel();
        $modelClass = key($modelData);
        $model = new $modelClass();
        $this->setFields($model, reset($modelData));

        return $model;
    }

    private function setFields(JsonSerializable $model, array $fields): void
    {
        $filter = array_filter($fields, function (?array $values): bool {
            return null !== $values;
        });

        array_walk($filter, function (array $values, string $field) use ($model): void {
            array_walk($values, function ($id, $class) use ($field, $model): void {
                $this->setField($model, $field, $class, $id);
            });
        });
    }

    private function setField(JsonSerializable $model, string $field, string $class, ?string $id): void
    {
        if (null === $id) {
            return;
        }

        $this->propertAccessor->setValue($model, $field, $this->repositories[$class]->find($id));
    }
}
