<?php

declare(strict_types=1);

namespace Domain\Entity\Translation;

use Domain\Entity\Scene;
use Domain\Entity\Traits\LocaleTrait;

class SceneTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Scene
     */
    private $scene;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setScene(?Scene $scene): void
    {
        $this->scene = $scene;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }
}
