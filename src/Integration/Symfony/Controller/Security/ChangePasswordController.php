<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Form;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Application\Security\ChangePassword;

class ChangePasswordController
{
    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

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
        FormHandlerFactoryInterface $formHandlerFactory,
        AuthorContext $authorContext,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Form\Type\Security\ChangePassword::class
        );
        if (true === $formHandler->isSubmissionValid()) {
            $this->commandBus->handle(new ChangePassword(
                $this->authorContext->getAuthor(),
                $formHandler->getData()['newPassword']
            ));

            $this->authorContext->logout();
            return $this->responseFactory->redirectToRoute('login');
        }

        return $this->responseFactory->fromTemplate(
            'security/changePassword.html.twig',
            ['form' => $formHandler->createView()]
        );
    }
}
