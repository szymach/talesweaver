<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Security;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Talesweaver\Application\Security\Command\ChangePassword;
use Talesweaver\Domain\User;
use Talesweaver\Integration\Form\Security\ChangePasswordType;

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
     * @var type
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
        return $this->tokenStorage->getToken()->getUser();
    }
}
