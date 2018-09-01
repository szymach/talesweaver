<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
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
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commmandBus,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commmandBus = $commmandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Location $location)
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($location),
            [
                'action' => $this->router->generate('location_edit', ['id' => $location->getId()]),
                'locationId' => $location->getId()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processForDataAndCreateResponse($location, $form->getData());
        }

        return $this->templating->createView($form, 'location.header.edit');
    }

    private function processForDataAndCreateResponse(Location $location, DTO $dto): Response
    {
        $this->commmandBus->handle(new Command(
            $location,
            new ShortText($dto->getName()),
            null !== $dto->getDescription() ? new LongText($dto->getDescription()) : null,
            null !== $dto->getAvatar() ? new File($dto->getAvatar()) : null
        ));

        return new JsonResponse(['success' => true]);
    }
}
