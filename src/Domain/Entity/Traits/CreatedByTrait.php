<?php

declare(strict_types=1);

namespace Domain\Entity\Traits;

use Domain\Entity\User;

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
