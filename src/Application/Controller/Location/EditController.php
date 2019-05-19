<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Location\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Location\Edit;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\LocationResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Location;

final class EditController
{
    /**
     * @var LocationResolver
     */
    private $locationResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        LocationResolver $locationResolver,
        ApiResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->locationResolver = $locationResolver;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $location = $this->locationResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($location),
            [
                'action' => $this->urlGenerator->generate('location_edit', ['id' => $location->getId()]),
                'locationId' => $location->getId()
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($location, $formHandler->getData());
        }

        return $this->responseFactory->form(
            'form\modalContent.html.twig',
            ['form' => $formHandler->createView()],
            $formHandler->displayErrors(),
            'location.header.edit'
        );
    }

    private function processFormDataAndRedirect(Location $location, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand($location));

        return $this->responseFactory->success();
    }
}
