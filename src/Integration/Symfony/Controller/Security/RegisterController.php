<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\CreateAuthor;
use Talesweaver\Integration\Symfony\Form\Security\RegisterType;

class RegisterController
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
        $form = $this->formFactory->create(RegisterType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->commandBus->handle(new CreateAuthor($data['email'], $data['password']));

            return $this->responseFactory->redirectToRoute('login');
        }

        return $this->responseFactory->fromTemplate(
            'security/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
