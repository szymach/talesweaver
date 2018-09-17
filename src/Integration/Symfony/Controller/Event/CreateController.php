<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Event\Create\Command;
use Talesweaver\Application\Event\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;

class CreateController
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

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        UrlGenerator $urlGenerator
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Scene $scene, string $model): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($scene),
            [
                'scene' => $scene,
                'model' => SceneEvents::getEventForm($model),
                'action' => $this->urlGenerator->generate(
                    'event_add',
                    ['id' => $scene->getId(), 'model' => $model]
                )
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($scene, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial/simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'event.header.new']
            )
        ], true === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): ResponseInterface
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(
            new Command($id, $scene, new ShortText($dto->getName()), $dto->getModel())
        );

        return $this->responseFactory->toJson(['success' => true]);
    }
}
