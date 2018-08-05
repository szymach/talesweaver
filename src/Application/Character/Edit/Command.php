<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Character
     */
    private $character;

    public function __construct(DTO $dto, Character $character)
    {
        $this->dto = $dto;
        $this->character = $character;
    }

    public function isAllowed(Author $author): bool
    {
        return $author->getId() === $this->character->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('character');
    }

    public function getData(): DTO
    {
        return $this->dto;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }
}
