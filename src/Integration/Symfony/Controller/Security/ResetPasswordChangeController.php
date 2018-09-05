<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\ResetPassword;
use Talesweaver\Domain\PasswordResetToken;
use Talesweaver\Domain\PasswordResetTokens;
use Talesweaver\Integration\Symfony\Form\Security\ResetPasswordChangeType;

class ResetPasswordChangeController
{
    /**
     * @var PasswordResetTokens
     */
    private $resetPasswordTokens;

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
        PasswordResetTokens $resetPasswordTokens,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->resetPasswordTokens = $resetPasswordTokens;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, string $code)
    {
        $token = $this->getToken($code);
        $form = $this->formFactory->create(ResetPasswordChangeType::class);
        if (true === $form->handleRequest($request)->isSubmitted() && true === $form->isValid()) {
            $this->commandBus->handle(new ResetPassword($token, $form->getData()['password']));

            return $this->responseFactory->redirectToRoute('index');
        }

        return $this->responseFactory->fromTemplate(
            'security/resetPasswordChange.html.twig',
            ['form' => $form->createView()]
        );
    }

    private function getToken(string $code): PasswordResetToken
    {
        $token = $this->resetPasswordTokens->findOneByCode($code);
        if (null === $token) {
            throw $this->responseFactory->notFound(sprintf(
                'No password reset token found for code "%s".',
                $code
            ));
        }

        if (false === $token->isValid()) {
            throw $this->responseFactory->notFound(
                sprintf('Token for code "%s" has expired.', $code)
            );
        }

        if (false === $token->isActive()) {
            throw $this->responseFactory->notFound(sprintf(
                'Token for code "%s" has already been used or made obsolete.',
                $code
            ));
        }

        return $token;
    }
}
