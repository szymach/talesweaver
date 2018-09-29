<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;

class AuthorByEmailHandler implements QueryHandlerInterface
{
    /**
     * @var Authors
     */
    private $authors;

    public function __construct(Authors $authors)
    {
        $this->authors = $authors;
    }

    public function __invoke(AuthorByEmail $query): ?Author
    {
        return $this->authors->findOneByEmail($query->getEmail());
    }
}
