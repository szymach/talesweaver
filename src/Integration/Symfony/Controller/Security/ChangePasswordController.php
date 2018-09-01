<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\AuthorContextInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Application\Security\ChangePassword;
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
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        AuthorContext $authorContext,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(ChangePasswordType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new ChangePassword(
                $this->authorContext->getAuthor(),
                $form->getData()['newPassword']
            ));

            $this->authorContext->logout();
            return new RedirectResponse($this->router->generate('login'));
        }

        return $this->templating->renderResponse(
            'security/changePassword.html.twig',
            ['form' => $form->createView()]
        );
    }
}
