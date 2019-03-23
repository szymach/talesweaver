<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Create;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    public function toCommand(UuidInterface $bookId): Command
    {
        Assertion::notNull($this->title);

        return new Command($bookId, new ShortText($this->title));
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
