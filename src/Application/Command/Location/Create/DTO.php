<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Create;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use SplFileInfo;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var File|SplFileInfo|null
     */
    private $avatar;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene)
    {
        $this->scene = $scene;
    }

    public function toCommand(Scene $scene, UuidInterface $id): Command
    {
        Assertion::notNull($this->name);

        return new Command(
            $scene,
            $id,
            new ShortText($this->name),
            LongText::fromNullableString($this->description),
            File::fromNullableValue($this->avatar)
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }
}
