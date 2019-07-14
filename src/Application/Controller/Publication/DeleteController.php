<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Publication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Publication\Delete\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\PublicationResolver;

final class DeleteController
{
    /**
     * @var PublicationResolver
     */
    private $publicationResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        PublicationResolver $publicationResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->publicationResolver = $publicationResolver;
        $this->commandBus = $commandBus;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $publication = $this->publicationResolver->fromRequest($request);
        $this->commandBus->dispatch(new Command($publication));

        return $this->apiResponseFactory->success();
    }
}
