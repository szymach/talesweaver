<?php

declare(strict_types=1);

namespace Domain\Entity\Traits;

use FSi\DoctrineExtensions\Uploadable\File;
use InvalidArgumentException;
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

    public function getAvatarKey(): ?string
    {
        return $this->avatarKey;
    }

    public function setAvatarKey(?string $avatarKey): void
    {
        $this->avatarKey = $avatarKey;

        if (method_exists($this, 'update')) {
            $this->update();
        }
    }

    private function validateAvatar($avatar): void
    {
        if (null !== $avatar && !($avatar instanceof File) && !($avatar instanceof SplFileInfo)) {
            throw new InvalidArgumentException(sprintf(
                'Avatar file must be either of instance "%s" or "%s", got "%s"',
                File::class,
                SplFileInfo::class,
                is_object($avatar) ? get_class($avatar) : gettype($avatar)
            ));
        }
    }
}
