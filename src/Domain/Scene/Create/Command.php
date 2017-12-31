<?php

declare(strict_types=1);

namespace Domain\Scene\Create;

use App\Bus\Messages\CreationSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Security\Traits\UserAwareTrait;
use Domain\Security\UserAwareInterface;
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
