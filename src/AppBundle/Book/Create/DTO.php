<?php

namespace AppBundle\Book\Create;

class DTO
{
    /**
     * @var string
     */
    private $title;

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
