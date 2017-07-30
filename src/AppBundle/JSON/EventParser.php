<?php

namespace AppBundle\JSON;

use AppBundle\Entity\Character;
use AppBundle\Entity\Event;
use AppBundle\Entity\Item;
use AppBundle\Entity\Location;
use AppBundle\Entity\Repository\CharacterRepository;
use AppBundle\Entity\Repository\ItemRepository;
use AppBundle\Entity\Repository\LocationRepository;
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

    public function parse(Event $event) : JsonSerializable
    {
        $modelData = $event->getModel();
        $modelClass = key($modelData);
        $model = new $modelClass();
        $this->setFields($model, reset($modelData));

        return $model;
    }

    private function setFields(JsonSerializable $model, array $fields)
    {
        foreach ($fields as $fieldName => $values) {
            if (is_null($values)) {
                continue;
            }

            foreach ($values as $entityClass => $id) {
                $this->setField($model, $fieldName, $entityClass, $id);
            }
        }
    }

    private function setField(JsonSerializable $model, string $fieldName, string $entityClass, ?string $id)
    {
        if (is_null($id)) {
            return;
        }

        $this->propertAccessor->setValue(
            $model,
            $fieldName,
            $this->repositories[$entityClass]->find($id)
        );
    }
}
