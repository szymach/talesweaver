<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Interfaces\LatestChangesAwareRepository;
use AppBundle\Repository\Traits\LatestResultsTrait;
use AppBundle\Repository\Traits\ValidationTrait;

class BookRepository extends TranslatableRepository implements LatestChangesAwareRepository
{
    use LatestResultsTrait, ValidationTrait;
}
