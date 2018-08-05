<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

use Talesweaver\Domain\ValueObject\File;

trait AvatarTrait
{
    /**
     * @var File|null
     */
    private $avatar;

    /**
     * @var string|null
     */
    private $avatarKey;

    public function getAvatar(): ?File
    {
        $this->transformFile();
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

    private function transformFile(): void
    {
        if (null === $this->avatar || true === $this->avatar instanceof File) {
            return;
        }

        $this->avatar = new File($this->avatar);
    }
}
