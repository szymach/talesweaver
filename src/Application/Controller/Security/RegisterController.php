<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Security\CreateAuthor;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Security\Register;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\ShortText;

final class RegisterController
{
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
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
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
            return $this->handleSubmissionAndRedirect($formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'security/register.html.twig',
            ['form' => $formHandler->createView()]
        );
    }

    private function handleSubmissionAndRedirect(array $data): ResponseInterface
    {
        $this->commandBus->dispatch(
            new CreateAuthor(
                new Email($data['email']),
                $data['password'],
                ShortText::nullableFromString($data['name']),
                ShortText::nullableFromString($data['surname'])
            )
        );

        return $this->responseFactory->redirectToRoute('login');
    }
}
