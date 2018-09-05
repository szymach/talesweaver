<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\GeneratePasswordResetToken;
use Talesweaver\Integration\Symfony\Form\Security\ResetPasswordRequestType;

class ResetPasswordRequestController
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $form = $this->formFactory->create(ResetPasswordRequestType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new GeneratePasswordResetToken($form->getData()['email']));

            return $this->responseFactory->redirectToRoute('index');
        }

        return $this->responseFactory->fromTemplate(
            'security/resetPasswordRequest.html.twig',
            ['form' => $form->createView()]
        );
    }
}
