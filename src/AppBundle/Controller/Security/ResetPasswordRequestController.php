<?php

declare(strict_types=1);

namespace AppBundle\Controller\Security;

use AppBundle\Form\Security\ResetPasswordRequestType;
use Domain\Security\Command\GeneratePasswordResetToken;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ResetPasswordRequestController
{
    /**
     * @var EngineInterface
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
        EngineInterface $templating,
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

            return new RedirectResponse($this->router->generate('app_index'));
        }

        return $this->templating->renderResponse(
            'security/resetPasswordRequest.html.twig',
            ['form' => $form->createView()]
        );
    }
}
