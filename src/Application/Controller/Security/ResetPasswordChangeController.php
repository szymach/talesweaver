<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Security\ResetPassword;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Security\ResetPassword\Change;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Security\PasswordResetTokenByCode;
use Talesweaver\Domain\PasswordResetToken;

final class ResetPasswordChangeController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

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
        QueryBus $queryBus,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->queryBus = $queryBus;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $token = $this->getToken($request->getAttribute('code'));
        $formHandler = $this->formHandlerFactory->createWithRequest($request, Change::class);
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
        $token = $this->queryBus->query(new PasswordResetTokenByCode($code));
        if (null === $token) {
            throw $this->responseFactory->notFound("No password reset token found for code \"{$code}\".");
        }

        if (false === $token->isValid()) {
            throw $this->responseFactory->notFound("Token for code \"{$code}\" has expired.");
        }

        if (false === $token->isActive()) {
            throw $this->responseFactory->notFound(
                "Token for code \"{$code}\" has already been used or made obsolete."
            );
        }

        return $token;
    }
}
