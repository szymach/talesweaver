<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Publication
{
    use CreatedByTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var LongText
     */
    private $content;

    /**
     * @var bool
     */
    private $visible;

    /**
     * @var string
     */
    private $locale;

    /**
     * @param UuidInterface $id
     * @param Author $createdBy
     * @param ShortText $title
     * @param LongText $content
     * @param bool $visible
     * @param string $locale
     */
    public function __construct(
        UuidInterface $id,
        Author $createdBy,
        ShortText $title,
        LongText $content,
        bool $visible,
        string $locale
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->visible = $visible;
        $this->locale = $locale;
        $this->createdBy = $createdBy;
        $this->createdAt = new DateTimeImmutable();
    }

    public function toggleVisibility(): void
    {
        $this->visible = !$this->visible;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getContent(): LongText
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
