<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

use Serializable;

final class Sort implements Serializable
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $direction;

    public function __construct(string $field, string $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    public function serialize(): string
    {
        return serialize(['field' => $this->field, 'direction' => $this->direction]);
    }

    public function unserialize($serialized)
    {
        $unserialized = unserialize($serialized);
        if (false === is_array($unserialized)) {
            // exception?
            return;
        }

        $this->field = $unserialized['field'];
        $this->direction = $unserialized['direction'];
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
