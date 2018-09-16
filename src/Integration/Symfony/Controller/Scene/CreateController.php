<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Scene\Create\Command;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest($request, Create::class, new DTO());
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'scene/createForm.html.twig',
            [
                'form' => $formHandler->createView(),
                'chapterId' => $this->getChapterId($formHandler->getData())
            ]
        );
    }

    private function processFormDataAndRedirect(DTO $dto): ResponseInterface
    {
        $id = Uuid::uuid4();
        $this->commandBus->handle(
            new Command($id, new ShortText($dto->getTitle()), $dto->getChapter())
        );

        return $this->responseFactory->redirectToRoute('scene_edit', ['id' => $id]);
    }

    private function getChapterId(DTO $dto): ?UuidInterface
    {
        $chapter = $dto->getChapter();
        return $chapter ? $chapter->getId(): null;
    }
}
