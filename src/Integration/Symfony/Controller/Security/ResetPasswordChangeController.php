<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Form;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\ResetPassword;
use Talesweaver\Domain\PasswordResetToken;
use Talesweaver\Domain\PasswordResetTokens;

class ResetPasswordChangeController
{
    /**
     * @var PasswordResetTokens
     */
    private $resetPasswordTokens;

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
        PasswordResetTokens $resetPasswordTokens,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->resetPasswordTokens = $resetPasswordTokens;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, string $code): ResponseInterface
    {
        $token = $this->getToken($code);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Form\Type\Security\ResetPassword\Change::class
        );
        if (true === $formHandler->isSubmissionValid()) {
            $this->commandBus->dispatch(new ResetPassword($token, $formHandler->getData()['password']));

            return $this->responseFactory->redirectToRoute('index');
        }

        return $this->responseFactory->fromTemplate(
            'security/resetPasswordChange.html.twig',
            ['form' => $formHandler->createView()]
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
