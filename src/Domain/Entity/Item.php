<?php

declare(strict_types=1);

namespace Domain\Entity;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Entity\Traits\AvatarTrait;
use Domain\Entity\Traits\CreatedByTrait;
use Domain\Entity\Traits\TimestampableTrait;
use Domain\Entity\Traits\TranslatableTrait;
use FSi\DoctrineExtensions\Uploadable\File;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use SplFileInfo;

class Item
{
    use AvatarTrait, CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Collection
     */
    private $translations;

    /**
     * @var Collection
     */
    private $scenes;

    /**
     * @var Collection
     */
    private $characters;

    /**
     * @var Collection
     */
    private $locations;

    /**
     * @param UuidInterface $id
     * @param Scene $scene
     * @param string $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @param User $author
     */
    public function __construct(
        UuidInterface $id,
        Scene $scene,
        string $name,
        ?string $description,
        $avatar,
        User $author
    ) {
        Assertion::notBlank($name, sprintf(
            'Cannot create an item without a name for author "%s"!',
            $author->getId()
        ));
        $this->validateAvatar($id, $avatar);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();

        $scene->addItem($this);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @return void
     */
    public function edit(string $name, ?string $description, $avatar): void
    {
        Assertion::notBlank($name, sprintf('Tried to set an empty name on item with id "%s"!', $this->id->toString()));

        $this->validateAvatar($avatar);

        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;

        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function addScene(Scene $scene): void
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes[] = $scene;
            $this->update();
        }
    }

    public function removeScene(Scene $scene): void
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    private function validateAvatar(UuidInterface $id, $avatar): void
    {
        if (null !== $avatar
            && false === $avatar instanceof File
            && false === $avatar instanceof SplFileInfo
        ) {
            throw new InvalidArgumentException(sprintf(
                'Item\'s "%s" avatar must be either of instance "%s" or "%s", got "%s"',
                $id->toString(),
                File::class,
                SplFileInfo::class,
                is_object($avatar) ? get_class($avatar) : gettype($avatar)
            ));
        }
    }
}
