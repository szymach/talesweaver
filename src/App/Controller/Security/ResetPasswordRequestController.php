<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Form\Security\ResetPasswordRequestType;
use App\Templating\Engine;
use Domain\Security\Command\GeneratePasswordResetToken;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ResetPasswordRequestController
{
    /**
     * @var Engine
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
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        Engine $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(ResetPasswordRequestType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new GeneratePasswordResetToken(
                $form->getData()['username']
            ));

            return new RedirectResponse($this->router->generate('index'));
        }

        return $this->templating->renderResponse(
            'security/resetPasswordRequest.html.twig',
            ['form' => $form->createView()]
        );
    }
}
