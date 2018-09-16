<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Location\Edit\Command;
use Talesweaver\Application\Location\Edit\DTO;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Location\EditType;

class EditController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
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
        ResponseFactoryInterface $responseFactory,
        FormFactoryInterface $formFactory,
        HtmlContent $htmlContent,
        MessageBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formFactory = $formFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Location $location): ResponseInterface
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($location),
            [
                'action' => $this->urlGenerator->generate('location_edit', ['id' => $location->getId()]),
                'locationId' => $location->getId()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processForDataAndCreateResponse($location, $form->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'location.header.edit']
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }

    private function processForDataAndCreateResponse(Location $location, DTO $dto): ResponseInterface
    {
        $this->commandBus->handle(new Command(
            $location,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
