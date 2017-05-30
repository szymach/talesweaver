<?php

namespace AppBundle\Chapter\Delete;

use AppBundle\Entity\Chapter;
use Ramsey\Uuid\Uuid;

class Command
{
    /**
     * @var int
     */
    private $id;

    public function __construct(Chapter $chapter)
    {
        $this->id = $chapter->getId();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
