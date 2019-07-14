<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Publication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Publication\ToggleVisibility\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\PublicationResolver;

final class TogglePublicVisibilityController
{
    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var PublicationResolver
     */
    private $publicationResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(
        ApiResponseFactoryInterface $responseFactory,
        PublicationResolver $publicationResolver,
        CommandBus $commandBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->publicationResolver = $publicationResolver;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $publication = $this->publicationResolver->fromRequest($request);
        $this->commandBus->dispatch(new Command($publication));

        return $this->responseFactory->success();
    }
}
