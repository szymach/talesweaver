<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

use Talesweaver\Domain\User;

trait CreatedByTrait
{
    /**
     * @var User
     */
    private $createdBy;

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }
}
