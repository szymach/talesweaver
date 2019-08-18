<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Domain\ValueObject\ShortText;

final class UpdateAuthorPeronalInformation
{
    /**
     * @var ShortText|null
     */
    private $name;

    /**
     * @var ShortText|null
     */
    private $surname;

    public function __construct(?ShortText $name, ?ShortText $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getName(): ?ShortText
    {
        return $this->name;
    }

    public function getSurname(): ?ShortText
    {
        return $this->surname;
    }
}
