<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Security\Traits\AuthorAwareTrait;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, AuthorAwareInterface, MessageCommandInterface
{
    use AuthorAwareTrait;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText|null
     */
    private $description;

    /**
     * @var File|null
     */
    private $avatar;

    public function __construct(
        Scene $scene,
        UuidInterface $id,
        ShortText $name,
        ?LongText $description,
        ?File $avatar
    ) {
        $this->scene = $scene;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function getAvatar(): ?File
    {
        return $this->avatar;
    }

    public function isAllowed(Author $author): bool
    {
        return $this->scene->getCreatedBy() === $author;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('character', ['%title%' => (string) $this->name]);
    }
}
