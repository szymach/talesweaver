<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Event\Create\Command;
use Talesweaver\Application\Event\Create\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;
use Talesweaver\Integration\Symfony\Form\Event\CreateType;
use Talesweaver\Integration\Symfony\Templating\Event\FormView;

class CreateController
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
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Scene $scene, string $model): ResponseInterface
    {
        $form = $this->formFactory->create(CreateType::class, new DTO($scene), [
            'scene' => $scene,
            'model' => SceneEvents::getEventForm($model),
            'action' => $this->responseFactory->generate('event_add', ['id' => $scene->getId(), 'model' => $model])
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($scene, $form->getData());
        }

        return $this->templating->createView($form);
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): ResponseInterface
    {
        $id = Uuid::uuid4();
        $this->commandBus->handle(new Command($id, $scene, new ShortText($dto->getName()), $dto->getModel()));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
