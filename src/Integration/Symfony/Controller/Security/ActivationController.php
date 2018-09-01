<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
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
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        AuthorRepository $repository,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(string $code)
    {
        $author = $this->repository->findOneByActivationToken($code);
        if (null === $author) {
            throw new NotFoundHttpException('No author for code "%s"');
        }

        $this->commandBus->handle(new ActivateAuthor($author));

        return new RedirectResponse($this->router->generate('login'));
    }
}
