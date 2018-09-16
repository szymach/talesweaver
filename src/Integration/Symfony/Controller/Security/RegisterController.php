<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Security\Register;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\CreateAuthor;

class RegisterController
{
    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest($request, Register::class);
        if (true === $formHandler->isSubmissionValid()) {
            $data = $formHandler->getData();
            $this->commandBus->handle(new CreateAuthor($data['email'], $data['password']));

            return $this->responseFactory->redirectToRoute('login');
        }

        return $this->responseFactory->fromTemplate(
            'security/register.html.twig',
            ['form' => $formHandler->createView()]
        );
    }
}
