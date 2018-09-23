<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;

class AuthorByTokenHandler implements QueryHandlerInterface
{
    /**
     * @var Authors
     */
    private $repository;

    public function __construct(Authors $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AuthorByToken $query): ?Author
    {
        return $this->repository->findOneByActivationToken($query->getToken());
    }
}
