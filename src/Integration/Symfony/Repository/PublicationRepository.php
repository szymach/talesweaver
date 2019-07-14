<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Publications;
use Talesweaver\Integration\Doctrine\Repository\PublicationRepository as DoctrineRepository;

final class PublicationRepository implements Publications
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(DoctrineRepository $doctrineRepository, AuthorContext $authorContext)
    {
        $this->doctrineRepository = $doctrineRepository;
        $this->authorContext = $authorContext;
    }

    public function find(UuidInterface $id): ?Publication
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $id);
    }
}
