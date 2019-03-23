<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Security;

use Assert\Assertion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Security\ChangePassword;
use Talesweaver\Application\Form;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;

final class ChangePasswordController
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
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        AuthorContext $authorContext,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Form\Type\Security\ChangePassword::class
        );
        if (true === $formHandler->isSubmissionValid()) {
            $this->handleFormSubmission($formHandler->getData());

            return $this->responseFactory->redirectToRoute('login');
        }

        return $this->responseFactory->fromTemplate(
            'security/changePassword.html.twig',
            ['form' => $formHandler->createView()]
        );
    }

    private function handleFormSubmission(array $formData): void
    {
        $author = $this->authorContext->getAuthor();
        Assertion::notNull($author);

        $newPassword = $formData['newPassword'];
        Assertion::notNull($newPassword);

        $this->commandBus->dispatch(new ChangePassword($author, $newPassword));
        $this->authorContext->logout();
    }
}
