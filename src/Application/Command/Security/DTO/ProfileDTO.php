<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security\DTO;

use Talesweaver\Application\Command\Security\UpdateAuthorPeronalInformation;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\ShortText;

final class ProfileDTO
{
    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
     */
    public $surname;

    public static function fromEntity(Author $author): self
    {
        $instance = new self();

        $instance->name = null !== $author->getName() ? (string) $author->getName() : null;
        $instance->surname = null !== $author->getSurname() ? (string) $author->getSurname() : null;

        return $instance;
    }

    public function toCommand(): UpdateAuthorPeronalInformation
    {
        return new UpdateAuthorPeronalInformation(
            ShortText::nullableFromString($this->name),
            ShortText::nullableFromString($this->surname)
        );
    }
}
