<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Positionable\IncreaseSingle;

use Talesweaver\Domain\Author;
use Talesweaver\Domain\Positionable;
use Talesweaver\Domain\Security\AuthorAccessInterface;

final class Command implements AuthorAccessInterface
{
    /**
     * @var Positionable
     */
    private $item;

    public function __construct(Positionable $item)
    {
        $this->item = $item;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->item->getCreatedBy();
    }

    public function getItem(): Positionable
    {
        return $this->item;
    }
}
