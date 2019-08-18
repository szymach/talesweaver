<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Security\DTO\ProfileDTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Security\Profile;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\AuthorContext;

final class ProfileController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHanderFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        AuthorContext $authorContext,
        FormHandlerFactoryInterface $formHanderFactory,
        CommandBus $commandBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->authorContext = $authorContext;
        $this->formHanderFactory = $formHanderFactory;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->formHanderFactory->createWithRequest(
            $request,
            Profile::class,
            ProfileDTO::fromEntity($this->authorContext->getAuthor())
        );

        if (true === $formHandler->isSubmissionValid()) {
            $this->handleFormSubmissionAndRedirect($formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'security/profile.html.twig',
            ['form' => $formHandler->createView()]
        );
    }

    private function handleFormSubmissionAndRedirect(ProfileDTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand());
        return $this->responseFactory->redirectToRoute('profile');
    }
}
