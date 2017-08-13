<?php

namespace AppBundle\Character\Edit;

use AppBundle\Entity\Character;
use AppBundle\Traits\IdentityTrait;
use FSi\DoctrineExtensions\Uploadable\File;
use SplFileInfo;

class DTO
{
    use IdentityTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var File|SplFileInfo
     */
    private $avatar;

    public function __construct(Character $character)
    {
        $this->id = $character->getId();
        $this->name = $character->getName();
        $this->avatar = $character->getAvatar();
        $this->description = $character->getDescription();
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
