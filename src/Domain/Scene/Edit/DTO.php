<?php

declare(strict_types=1);

namespace Domain\Scene\Edit;

use App\Entity\Chapter;
use App\Entity\Scene;
use Domain\Model\IdentityTrait;

class DTO
{
    use IdentityTrait;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct(Scene $scene)
    {
        $this->id = $scene->getId();
        $this->title = $scene->getTitle();
        $this->text = $scene->getText();
        $this->chapter = $scene->getChapter();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): void
    {
        $this->chapter = $chapter;
    }
}
