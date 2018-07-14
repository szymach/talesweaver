<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Integration\Symfony\Bus\Command\ActivateUser;
use Talesweaver\Integration\Doctrine\Repository\UserRepository;

class ActivationController
{
    /**
     * @var UserRepository
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
        UserRepository $repository,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(string $code)
    {
        $user = $this->repository->findOneByActivationToken($code);
        if (null === $user) {
            throw new NotFoundHttpException('No user for code "%s"');
        }

        $this->commandBus->handle(new ActivateUser($user));

        return new RedirectResponse($this->router->generate('login'));
    }
}
