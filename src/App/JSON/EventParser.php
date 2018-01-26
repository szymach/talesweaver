<?php

declare(strict_types=1);

namespace App\JSON;

use App\Entity\Character;
use App\Entity\Event;
use App\Entity\Item;
use App\Entity\Location;
use App\Repository\CharacterRepository;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
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
        if (is_null($id)) {
            return;
        }

        $this->propertAccessor->setValue($model, $field, $this->repositories[$class]->find($id));
    }
}
