<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Chapter\Publish\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\Type\Chapter\Publish;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\UrlGenerator;

final class PublishController
{
    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiRresponseFactory;

    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(
        ApiResponseFactoryInterface $apiResponseFactory,
        ChapterResolver $chapterResolver,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator,
        CommandBus $commandBus
    ) {
        $this->apiRresponseFactory = $apiResponseFactory;
        $this->chapterResolver = $chapterResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->createFormHandler($request);
        if (true === $formHandler->isSubmissionValid()) {
            $this->handleFormSubmission($formHandler->getData());
            return $this->apiRresponseFactory->success();
        }

        return $this->apiRresponseFactory->form(
            'form\modalContent.html.twig',
            ['form' => $formHandler->createView()],
            $formHandler->displayErrors(),
            'publication.header'
        );
    }

    private function createFormHandler(ServerRequestInterface $request): FormHandlerInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Publish::class,
            DTO::fromEntity($chapter),
            ['action' => $this->urlGenerator->generate('chapter_publish', ['id' => $chapter->getId()])]
        );
    }

    private function handleFormSubmission(DTO $data): void
    {
        $this->commandBus->dispatch($data->toCommand());
    }
}
