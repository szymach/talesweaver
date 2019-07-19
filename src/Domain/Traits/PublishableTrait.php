<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

use Assert\Assertion;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

/**
 * @property UuidInterface $id
 * @property string $locale
 * @property Author $createdBy
 */
trait PublishableTrait
{
    /**
     * @var Collection<Publication>
     */
    private $publications;

    public function publish(ShortText $title, LongText $parsedContent, bool $visible): void
    {
        Assertion::notNull(
            $this->locale,
            sprintf(
                'Cannot publish "%s" "%s" without a locale',
                get_class($this),
                $this->id->toString()
            )
        );

        $this->publications->add(
            new Publication(
                Uuid::uuid4(),
                $this->createdBy,
                $title,
                $parsedContent,
                $visible,
                $this->locale
            )
        );
    }

    public function getPublications(): array
    {
        return $this->publications->toArray();
    }

    public function getCurrentPublication(string $locale): ?Publication
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('locale', $locale))
            ->orderBy(['createdAt' => 'DESC'])
            ->setMaxResults(1)
        ;

        $result = $this->publications->matching($criteria)->first();
        return true === $result instanceof Publication ? $result : null;
    }
}
