<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Assert\Assertion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Positionable\UpdateMultiple\Command;
use Talesweaver\Application\Command\Positionable\UpdateMultiple\DTO;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\PositionableRequestResolver;
use Talesweaver\Application\Query\Scene\ByIds;
use Talesweaver\Domain\Scene;

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

        $scenes = $this->queryBus->query(new ByIds($ids));
        $dtos = array_map(
            function (array $item) use ($scenes): DTO {
                return new DTO(
                    $this->idToScene($item['id'], $scenes),
                    $item['position']
                );
            },
            $parsedRequest
        );

        $this->commandBus->dispatch(new Command($dtos));
        return $this->responseFactory->success();
    }

    public function idToScene(UuidInterface $id, array $scenes): Scene
    {
        $scene = array_reduce(
            $scenes,
            function (?Scene $accumulator, Scene $scenes) use ($id): ?Scene {
                if (null !== $accumulator) {
                    return $accumulator;
                }

                if (true === $scenes->getId()->equals($id)) {
                    $accumulator = $scenes;
                }

                return $accumulator;
            }
        );

        Assertion::notNull($scene);
        return $scene;
    }
}
