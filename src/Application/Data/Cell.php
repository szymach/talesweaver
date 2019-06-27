<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

final class Cell
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
