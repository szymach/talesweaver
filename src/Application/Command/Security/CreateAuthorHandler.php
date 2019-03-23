<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Bus\EventBus;
use Talesweaver\Application\Event\AuthorRegistered;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;
use function generate_user_token;

final class CreateAuthorHandler implements CommandHandlerInterface
{
    /**
     * @var Authors
     */
    private $authors;

    /**
     * @var EventBus
     */
    private $eventBus;

    public function __construct(Authors $authors, EventBus $eventBus)
    {
        $this->authors = $authors;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateAuthor $command): void
    {
        $author = new Author(
            Uuid::uuid4(),
            $command->getEmail(),
            $command->getPassword(),
            generate_user_token()
        );

        $this->authors->add($author);
        $this->eventBus->send(new AuthorRegistered($author));
    }
}
