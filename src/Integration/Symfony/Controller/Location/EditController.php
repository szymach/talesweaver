<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Location\Edit\Command;
use Talesweaver\Application\Location\Edit\DTO;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Location\EditType;
use Talesweaver\Integration\Symfony\Templating\Location\FormView;

class EditController
{
    /**
     * @var FormView
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commmandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commmandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commmandBus = $commmandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Location $location): ResponseInterface
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($location),
            [
                'action' => $this->responseFactory->generate('location_edit', ['id' => $location->getId()]),
                'locationId' => $location->getId()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processForDataAndCreateResponse($location, $form->getData());
        }

        return $this->templating->createView($form, 'location.header.edit');
    }

    private function processForDataAndCreateResponse(Location $location, DTO $dto): ResponseInterface
    {
        $this->commmandBus->handle(new Command(
            $location,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
