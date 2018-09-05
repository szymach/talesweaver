<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Application\Security\ChangePassword;
use Talesweaver\Integration\Symfony\Form\Security\ChangePasswordType;

class ChangePasswordController
{
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        AuthorContext $authorContext,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $form = $this->formFactory->create(ChangePasswordType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new ChangePassword(
                $this->authorContext->getAuthor(),
                $form->getData()['newPassword']
            ));

            $this->authorContext->logout();
            return $this->responseFactory->redirectToRoute('login');
        }

        return $this->responseFactory->fromTemplate(
            'security/changePassword.html.twig',
            ['form' => $form->createView()]
        );
    }
}
