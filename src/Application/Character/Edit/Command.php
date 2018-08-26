<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Character
     */
    private $character;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText
     */
    private $description;

    /**
     * @var File|null
     */
    private $avatar;

    public function __construct(Character $character, ShortText $name, ?LongText $description, ?File $avatar)
    {
        $this->character = $character;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;
    }

    public function getCharacter(): Character
    {
        return $this->character;
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
        return $author === $this->character->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('character');
    }
}
