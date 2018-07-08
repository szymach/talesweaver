<?php

declare(strict_types=1);

namespace Integration\Controller\Security;

use Integration\Form\Security\ResetPasswordChangeType;
use Integration\Repository\PasswordResetTokenRepository;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Domain\User\PasswordResetToken;
use Application\Security\Command\ResetPassword;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

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
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(
                new ResetPassword($token, $form->getData()['password'])
            );

            return new RedirectResponse($this->router->generate('index'));
        }

        return $this->templating->renderResponse(
            'security/resetPasswordChange.html.twig',
            ['form' => $form->createView()]
        );
    }

    private function getToken(string $code): PasswordResetToken
    {
        /* @var $token PasswordResetToken */
        $token = $this->resetPasswordTokenRepository->findOneByCode($code);
        if (!$token) {
            $this->throwNotFoundException(
                'No password reset token found for code "%s".',
                $code
            );
        }

        if (!$token->isValid()) {
            $this->throwNotFoundException('Token for code "%s" has expired.', $code);
        }

        if (!$token->isActive()) {
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
