<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Positionable\UpdateMultiple;

use Assert\Assertion;
use Exception;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAccessInterface;

final class Command implements AuthorAccessInterface
{
    /**
     * @var DTO[]
     */
    private $items;

    /**
     * @var Author
     */
    private $author;

    public function __construct(array $items)
    {
        Assertion::allIsInstanceOf($items, DTO::class);

        $this->author = $this->getAuthor($items);
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->author;
    }

    private function getAuthor(array $items): Author
    {
        $author = array_reduce(
            $items,
            function (?Author $accumulator, DTO $dto): ?Author {
                $author = $dto->getPositionable()->getCreatedBy();
                if (null !== $accumulator && $author !== $accumulator) {
                    throw new Exception(sprintf(
                        'Tried to change position for items of class "%s" of two different authors: "%s" and "%s"',
                        get_class($dto->getPositionable()),
                        $accumulator->getId()->toString(),
                        $author->getId()->toString()
                    ));
                }

                if (null === $accumulator) {
                    $accumulator = $author;
                }

                return $accumulator;
            }
        );

        Assertion::notNull($author, "No user supplied!");
        return $author;
    }
}
