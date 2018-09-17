<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Security\ResetPassword;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\GeneratePasswordResetToken;

class ResetPasswordRequestController
{
    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $formHandler = $this->formHandlerFactory->createWithRequest($request, ResetPassword\Request::class);
        if (true === $formHandler->isSubmissionValid()) {
            $this->commandBus->dispatch(new GeneratePasswordResetToken($formHandler->getData()['email']));

            return $this->responseFactory->redirectToRoute('index');
        }

        return $this->responseFactory->fromTemplate(
            'security/resetPasswordRequest.html.twig',
            ['form' => $formHandler->createView()]
        );
    }
}
