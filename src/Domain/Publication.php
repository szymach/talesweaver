<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\ValueObject\LongText;

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

    public function __construct(UuidInterface $id, LongText $content, bool $visible, string $locale)
    {
        $this->id = $id;
        $this->content = $content;
        $this->visible = $visible;
        $this->locale = $locale;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getContent(): LongText
    {
        return $this->content;
    }
}
