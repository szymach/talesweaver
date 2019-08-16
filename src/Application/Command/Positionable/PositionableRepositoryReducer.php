<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Positionable;

use RuntimeException;
use Talesweaver\Domain\Positionable;
use Talesweaver\Domain\PositionableRepository;

trait PositionableRepositoryReducer
{
    /**
     * @var iterable<PositionableRepository>
     */
    private $positionableRepositories;

    public function __construct(iterable $positionableRepositories)
    {
        $this->positionableRepositories = $positionableRepositories;
    }

    private function getRepository(Positionable $item): PositionableRepository
    {
        foreach ($this->positionableRepositories as $repository) {
            if (true === $repository->supportsPositionable($item)) {
                return $repository;
            }
        }

        throw new RuntimeException(
            sprintf('Item of class "%s" has no repository that supports it!', get_class($item))
        );
    }
}
