<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Assert\Assertion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\UpdatePositionMultiple\Command;
use Talesweaver\Application\Command\Chapter\UpdatePositionMultiple\DTO;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\PositionableRequestResolver;
use Talesweaver\Application\Query\Chapter\ByIds;
use Talesweaver\Domain\Chapter;

final class PositionController
{
    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var PositionableRequestResolver
     */
    private $requestResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(
        ApiResponseFactoryInterface $responseFactory,
        PositionableRequestResolver $requestResolver,
        QueryBus $queryBus,
        CommandBus $commandBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->requestResolver = $requestResolver;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $parsedRequest = $this->requestResolver->resolve($request);
        $ids = array_map(function (array $item): UuidInterface {
            return $item['id'];
        }, $parsedRequest);

        $chapters = $this->queryBus->query(new ByIds($ids));
        $dtos = array_map(
            function (array $item) use ($chapters): DTO {
                return new DTO(
                    $this->idToChapter($item['id'], $chapters),
                    $item['position']
                );
            },
            $parsedRequest
        );

        $this->commandBus->dispatch(new Command($dtos));
        return $this->responseFactory->success();
    }

    public function idToChapter(UuidInterface $id, array $chapters): Chapter
    {
        $chapter = array_reduce(
            $chapters,
            function (?Chapter $accumulator, Chapter $chapter) use ($id): ?Chapter {
                if (null !== $accumulator) {
                    return $accumulator;
                }

                if (true === $chapter->getId()->equals($id)) {
                    $accumulator = $chapter;
                }

                return $accumulator;
            }
        );

        Assertion::notNull($chapter);
        return $chapter;
    }
}
