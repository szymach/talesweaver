<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Security\ActivateAuthor;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Security\AuthorByToken;
use Talesweaver\Domain\Author;

class ActivationController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(
            new ActivateAuthor($this->getAuthor($request->getAttribute('code')))
        );

        return $this->responseFactory->redirectToRoute('login');
    }

    private function getAuthor(?string $code): Author
    {
        $author = $this->queryBus->query(new AuthorByToken($code));
        if (false === $author instanceof Author) {
            throw $this->responseFactory->notFound(sprintf('No author for code "%s"', $code));
        }

        return $author;
    }
}
