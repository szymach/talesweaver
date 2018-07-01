<?php

declare(strict_types=1);

namespace Domain\Traits;

use Domain\User;

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
