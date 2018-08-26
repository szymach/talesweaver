<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Create;

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
    private $title;

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
        ShortText $title,
        ?LongText $description,
        ?File $avatar
    ) {
        $this->scene = $scene;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->avatar = $avatar;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
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
        return $author === $this->scene->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('item', ['%title%' => $this->dto->getName()]);
    }
}
