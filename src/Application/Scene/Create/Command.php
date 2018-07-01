<?php

declare(strict_types=1);

namespace Application\Scene\Create;

use Application\Messages\CreationSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Application\Security\Traits\UserAwareTrait;
use Application\Security\UserAwareInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements MessageCommandInterface, UserAwareInterface
{
    use UserAwareTrait;

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
