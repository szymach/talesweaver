<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

use FSi\DoctrineExtensions\Uploadable;
use Talesweaver\Domain\ValueObject\File;

trait AvatarTrait
{
    /**
     * @var Uploadable\File|File|null
     */
    private $avatar;

    /**
     * @var string|null
     */
    private $avatarKey;

    public function getAvatar(): ?File
    {
        if (null !== $this->avatar && false === $this->avatar instanceof File) {
            $this->avatar = new File($this->avatar);
        }

        return $this->avatar;
    }

    public function setAvatar(?File $avatar): void
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

        $this->update();
    }
}
