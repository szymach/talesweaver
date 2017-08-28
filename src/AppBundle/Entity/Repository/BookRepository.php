<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Repository\Interfaces\LatestChangesAwareRepository;
use AppBundle\Entity\Repository\Traits\LatestResultsTrait;
use AppBundle\Entity\Repository\Traits\ValidationTrait;

class BookRepository extends TranslatableRepository implements LatestChangesAwareRepository
{
    use LatestResultsTrait, ValidationTrait;
}
