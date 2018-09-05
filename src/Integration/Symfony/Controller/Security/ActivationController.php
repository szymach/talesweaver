<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\ActivateAuthor;
use Talesweaver\Integration\Doctrine\Repository\AuthorRepository;

class ActivationController
{
    /**
     * @var AuthorRepository
     */
    private $repository;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        AuthorRepository $repository,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(string $code)
    {
        $author = $this->repository->findOneByActivationToken($code);
        if (null === $author) {
            throw $this->responseFactory->notFound(sprintf('No author for code "%s"', $code));
        }

        $this->commandBus->handle(new ActivateAuthor($author));

        return $this->responseFactory->redirectToRoute('login');
    }
}
