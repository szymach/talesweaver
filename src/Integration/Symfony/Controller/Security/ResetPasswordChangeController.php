<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Integration\Doctrine\Entity\PasswordResetToken;
use Talesweaver\Integration\Doctrine\Repository\PasswordResetTokenRepository;
use Talesweaver\Integration\Symfony\Bus\Command\ResetPassword;
use Talesweaver\Integration\Symfony\Form\Security\ResetPasswordChangeType;

class ResetPasswordChangeController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var PasswordResetTokenRepository
     */
    private $resetPasswordTokenRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

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
        PasswordResetTokenRepository $resetPasswordTokenRepository,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->resetPasswordTokenRepository = $resetPasswordTokenRepository;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, string $code)
    {
        $token = $this->getToken($code);
        $form = $this->formFactory->create(ResetPasswordChangeType::class);
        if (true === $form->handleRequest($request)->isSubmitted() && true === $form->isValid()) {
            $this->commandBus->handle(new ResetPassword($token, $form->getData()['password']));

            return new RedirectResponse($this->router->generate('index'));
        }

        return $this->templating->renderResponse(
            'security/resetPasswordChange.html.twig',
            ['form' => $form->createView()]
        );
    }

    private function getToken(string $code): PasswordResetToken
    {
        $token = $this->resetPasswordTokenRepository->findOneByCode($code);
        if (null === $token) {
            $this->throwNotFoundException(
                'No password reset token found for code "%s".',
                $code
            );
        }

        if (false === $token->isValid()) {
            $this->throwNotFoundException('Token for code "%s" has expired.', $code);
        }

        if (false === $token->isActive()) {
            $this->throwNotFoundException(
                'Token for code "%s" has already been used or made obsolete.',
                $code
            );
        }

        return $token;
    }

    private function throwNotFoundException(string $message, string $code): void
    {
        throw new NotFoundHttpException(sprintf($message, $code));
    }
}
