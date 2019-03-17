<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Event;

use DomainException;
use JsonSerializable;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Death implements JsonSerializable, AuthorAccessInterface
{
    /**
     * @var Character|null
     */
    private $character;

    /**
     * @var Location|null
     */
    private $location;

    public function jsonSerialize(): array
    {
        return [
            self::class => [
                'character' => $this->character ? [Character::class => $this->character->getId()] : null,
                'location' => $this->location ? [Location::class => $this->location->getId()] : null
            ]
        ];
    }

    public function isAllowed(Author $author): bool
    {
        return (null !== $this->character && $this->character->getCreatedBy() === $author)
            && (null !== $this->location && $this->location->getCreatedBy() === $author)
        ;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(?Character $character): void
    {
        $this->character = $character;
        $this->assertSameAuthor();
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
        $this->assertSameAuthor();
    }

    private function assertSameAuthor(): void
    {
        $characterAuthor = null !== $this->character ? $this->character->getCreatedBy() : null;
        $locationAuthor = null !== $this->location ? $this->location->getCreatedBy() : null;

        if (null !== $characterAuthor && null !== $locationAuthor && $characterAuthor !== $locationAuthor) {
            throw new DomainException(sprintf(
            'Author mismatch for fields "character" (user: "%s") and "location" (user: "%s")',
            $characterAuthor->getId()->toString(),
            $locationAuthor->getId()->toString()
        ));
        }
    }
}
