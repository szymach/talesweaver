<?php

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\User;

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
