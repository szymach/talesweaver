<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\User;

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
