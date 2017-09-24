<?php

namespace AppBundle\Book\Create;

use AppBundle\Security\Traits\UserAwareTrait;
use AppBundle\Security\UserAwareInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements UserAwareInterface
{
    use UserAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    public function __construct(UuidInterface $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }
}
