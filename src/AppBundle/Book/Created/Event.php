<?php

namespace AppBundle\Book\Created;

class Event
{
    /**
     * @var integer
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
