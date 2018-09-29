<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\JSON;

use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Query;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;

class EventParser
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertAccessor;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        PropertyAccessorInterface $propertAccessor,
        QueryBus $queryBus
    ) {
        $this->propertAccessor = $propertAccessor;
        $this->queryBus = $queryBus;
    }

    public function parse(array $modelData): JsonSerializable
    {
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

        $this->propertAccessor->setValue(
            $model,
            $field,
            $this->getObject($class, Uuid::fromString($id))
        );
    }

    private function getObject(string $class, UuidInterface $id): object
    {
        switch ($class) {
            case Character::class:
                $query = new Query\Character\ById($id);
                break;
            case Item::class;
                $query = new Query\Item\ById($id);
                break;
            case Location::class;
                $query = new Query\Location\ById($id);
                break;
            default:
                throw new RuntimeException(sprintf('Cannot match entity to class "%s"', $class));
        }

        $object = $this->queryBus->query($query);
        if (false === $object instanceof $class) {
            throw new RuntimeException(
                sprintf('No entity found for class and id "%s" "%s"', $class, $id->toString())
            );
        }

        return $object;
    }
}
