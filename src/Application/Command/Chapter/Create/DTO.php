<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Create;

class DTO
{
    /**
     * @var string
     */
    private $title;

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
