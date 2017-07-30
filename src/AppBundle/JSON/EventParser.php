<?php

namespace AppBundle\JSON;

use AppBundle\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use JsonSerializable;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class EventParser
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertAccessor;

    public function __construct(
        EntityManagerInterface $manager,
        PropertyAccessorInterface $propertAccessor
    ) {
        $this->manager = $manager;
        $this->propertAccessor = $propertAccessor;
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
            $this->manager->getRepository($entityClass)->find($id)
        );
    }
}
