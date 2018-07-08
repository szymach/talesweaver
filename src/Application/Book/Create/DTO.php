<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Create;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

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
