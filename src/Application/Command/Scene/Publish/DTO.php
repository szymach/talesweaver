<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Publish;

use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var Scene
     */
    public $scene;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var bool
     */
    public $visible;

    public static function fromEntity(Scene $scene): self
    {
        $instance = new self;
        $instance->scene = $scene;
        $instance->visible = false;

        return $instance;
    }

    public function toCommand(): Command
    {
        if (null !== $this->title && '' !== $this->title) {
            $title = new ShortText($this->title);
        } else {
            $title = $this->scene->getTitle();
        }

        return new Command($this->scene, $title, $this->visible);
    }

    private function __construct()
    {
    }
}
