<?php

declare(strict_types=1);

namespace Domain\Traits;

use SplFileInfo;

trait AvatarTrait
{
    /**
     * @var SplFileInfo
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

    public function getAvatarKey(): ?string
    {
        return $this->avatarKey;
    }

    public function setAvatarKey(?string $avatarKey): void
    {
        $this->avatarKey = $avatarKey;

        if (true === method_exists($this, 'update')) {
            $this->update();
        }
    }
}
