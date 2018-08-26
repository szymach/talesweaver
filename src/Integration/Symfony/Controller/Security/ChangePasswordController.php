<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use RuntimeException;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Talesweaver\Integration\Doctrine\Entity\User;
use Talesweaver\Integration\Symfony\Bus\Command\ChangePassword;
use Talesweaver\Integration\Symfony\Form\Security\ChangePasswordType;

class ChangePasswordController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

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
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(ChangePasswordType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(
                new ChangePassword($this->getUser(), $form->getData()['newPassword'])
            );

            $this->tokenStorage->setToken(null);
            return new RedirectResponse($this->router->generate('login'));
        }

        return $this->templating->renderResponse(
            'security/changePassword.html.twig',
            ['form' => $form->createView()]
        );
    }

    private function getUser(): User
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new RuntimeException('No user logged in');
        }

        $user = $token->getUser();
        if (false === $user instanceof User) {
            throw new RuntimeException(sprintf(
                'Expected instance of "%s", got "%s"',
                User::class,
                true === is_object($user) ? get_class($user) : gettype($user)
            ));
        }

        return $user;
    }
}
