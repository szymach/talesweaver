<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Command\Security\Traits\AuthorAwareTrait;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, AuthorAwareInterface, MessageCommandInterface
{
    use AuthorAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var ShortText
     */
    private $name;

    public function __construct(UuidInterface $id, Scene $scene, ShortText $name)
    {
        $this->id = $id;
        $this->scene = $scene;
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->scene->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('event', ['%title%' => $this->name]);
    }
}
