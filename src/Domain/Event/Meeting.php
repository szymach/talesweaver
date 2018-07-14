<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Event;

use DomainException;
use JsonSerializable;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Meeting implements JsonSerializable, AuthorAccessInterface
{
    /**
     * @var Character
     */
    private $root;

    /**
     * @var Location
     */
    private $location;

    /**
     * @var Character
     */
    private $relation;

    public function jsonSerialize(): array
    {
        return [
            self::class => [
                'root' => $this->root ? [Character::class => $this->root->getId()] : null,
                'location' => $this->location ? [Location::class => $this->location->getId()] : null,
                'relation' => $this->relation ? [Character::class => $this->relation->getId()] : null,
            ]
        ];
    }

    public function isAllowed(Author $author): bool
    {
        return (null !== $this->root && $this->root->getCreatedBy() === $author)
            && (null !== $this->location && $this->location->getCreatedBy() === $author)
            && (null !== $this->relation && $this->relation->getCreatedBy() === $author)
        ;
    }

    public function getRoot(): ?Character
    {
        return $this->root;
    }

    public function setRoot(?Character $root): void
    {
        $this->root = $root;
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

    public function getRelation(): ?Character
    {
        return $this->relation;
    }

    public function setRelation(?Character $relation): void
    {
        $this->relation = $relation;
        $this->assertSameAuthor();
    }

    private function assertSameAuthor(): void
    {
        $rootAuthor = (null !== $this->root) ? $this->root->getCreatedBy() : null;
        $relationAuthor = (null !== $this->relation) ? $this->relation->getCreatedBy() : null;
        $locationAuthor = (null !== $this->location) ? $this->location->getCreatedBy() : null;

        if (null !== $rootAuthor && null !== $relationAuthor  && $rootAuthor !== $relationAuthor) {
            $this->throwAuthorMismatchException('root', 'relation', $rootAuthor, $relationAuthor);
        }

        if (null !== $rootAuthor && null !== $locationAuthor && $rootAuthor !== $locationAuthor) {
            $this->throwAuthorMismatchException('root', 'location', $rootAuthor, $locationAuthor);
        }

        if (null !== $relationAuthor && null !== $locationAuthor && $relationAuthor !== $locationAuthor) {
            $this->throwAuthorMismatchException('relation', 'location', $relationAuthor, $locationAuthor);
        }
    }

    private function throwAuthorMismatchException(
        string $field1,
        string $field2,
        Author $field1Author,
        Author $field2Author
    ): void {
        throw new DomainException(sprintf(
            'Author mismatch for fields "%s" (user: "%s") and "%s" (user: "%s")',
            $field1,
            $field1Author->getId()->toString(),
            $field2,
            $field2Author->getId()->toString()
        ));
    }
}
