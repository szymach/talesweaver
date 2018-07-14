<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Security\Traits\AuthorAwareTrait;
use Talesweaver\Domain\Security\AuthorAwareInterface;

class Command implements MessageCommandInterface, AuthorAwareInterface
{
    use AuthorAwareTrait;

    /**
     * @var type
     */
    private $id;

    /**
     * @var DTO
     */
    private $dto;

    public function __construct(UuidInterface $id, DTO $dto)
    {
        $this->id = $id;
        $this->dto = $dto;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getData(): DTO
    {
        return $this->dto;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('scene', ['%title%' => $this->dto->getTitle()]);
    }
}
