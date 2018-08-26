<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event\Create;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Security\Traits\AuthorAwareTrait;
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

    /**
     * @var JsonSerializable|null
     */
    private $model;

    public function __construct(UuidInterface $id, Scene $scene, ShortText $name, JsonSerializable $model)
    {
        $this->id = $id;
        $this->scene = $scene;
        $this->name = $name;
        $this->model = $model;
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

    public function getModel(): JsonSerializable
    {
        return $this->model;
    }

    public function isAllowed(Author $author): bool
    {
        if ($this->model instanceof AuthorAccessInterface && false === $this->model->isAllowed($author)) {
            return false;
        }

        return $author === $this->scene->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('event', ['%title%' => $this->name]);
    }
}
