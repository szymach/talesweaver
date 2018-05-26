<?php

declare(strict_types=1);

namespace Domain\Event;

use Domain\Entity\Author;
use Domain\Entity\Character;
use Domain\Entity\Location;
use Domain\Entity\User;
use Domain\Security\UserAccessInterface;
use DomainException;
use JsonSerializable;

class Meeting implements JsonSerializable, UserAccessInterface
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

    public function isAllowed(User $user): bool
    {
        return (null === $this->root && $this->root->getCreatedBy() === $user)
            && (null === $this->location && $this->location->getCreatedBy() === $user)
            && (null === $this->relation && $this->relation->getCreatedBy() === $user)
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
        User $field1Author,
        User $field2Author
    ): void {
        throw new DomainException(sprintf(
            'Author mismatch for fields "%s" (user: "%s") and "%s" (user: "%s")',
            $field1,
            $field1Author->getId(),
            $field2,
            $field2Author->getId()
        ));
    }
}
