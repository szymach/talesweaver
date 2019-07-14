<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Publication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\PublicationResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class PublicDisplayController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var PublicationResolver
     */
    private $publicationResolver;

    public function __construct(ResponseFactoryInterface $responseFactory, PublicationResolver $publicationResolver)
    {
        $this->responseFactory = $responseFactory;
        $this->publicationResolver = $publicationResolver;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $publication = $this->publicationResolver->publicFromRequest($request);

        return $this->responseFactory->fromString((string) $publication->getContent());
    }
}
