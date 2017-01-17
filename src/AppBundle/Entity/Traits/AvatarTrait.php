<?php

namespace AppBundle\Entity\Traits;

use FSi\DoctrineExtensions\Uploadable\File;
use SplFileInfo;

trait AvatarTrait
{
    /**
     * @var File|SplFileInfo
     */
    private $avatar;

    /**
     * @var string
     */
    private $avatarKey;

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getAvatarKey()
    {
        return $this->avatarKey;
    }

    public function setAvatarKey($avatarKey)
    {
        $this->avatarKey = $avatarKey;

        if (method_exists($this, 'update')) {
            $this->update();
        }
    }
}
