<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

use Talesweaver\Domain\Author;

trait CreatedByTrait
{
    /**
     * @var Author
     */
    private $createdBy;

    public function getCreatedBy(): Author
    {
        return $this->createdBy;
    }
}
