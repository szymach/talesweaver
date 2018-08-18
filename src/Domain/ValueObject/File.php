<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

class File
{
    /**
     * @var object
     */
    private $value;

    public function __construct(object $value)
    {
        $this->value = $value;
    }

    public function getValue(): object
    {
        return $this->value;
    }
}
