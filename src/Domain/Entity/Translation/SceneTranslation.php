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
}
