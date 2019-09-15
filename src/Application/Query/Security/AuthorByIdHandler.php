<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;

final class AuthorByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Authors
     */
    private $authors;

    public function __construct(Authors $authors)
    {
        $this->authors = $authors;
    }

    public function __invoke(AuthorById $query): ?Author
    {
        return $this->authors->findOneById($query->getId());
    }
}
