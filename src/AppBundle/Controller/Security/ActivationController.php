<?php

declare(strict_types=1);

namespace AppBundle\Controller\Security;

use AppBundle\Repository\UserRepository;
use Domain\Security\Command\ActivateUser;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ActivationController
{
    /**
     * @var EngineInterface
     */
    private $templating;

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
        EngineInterface $templating,
        UserRepository $repository,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(string $code)
    {
        $user = $this->repository->findOneByActivationCode($code);
        if (!$user) {
            throw new NotFoundHttpException('No user for code "%s"');
        }

        $this->commandBus->handle(new ActivateUser($user));

        return new RedirectResponse($this->router->generate('login'));
    }
}
