<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Command\Scene\Create\Command;
use Talesweaver\Application\Command\Scene\Create\DTO;
use Talesweaver\Domain\ValueObject\ShortText;

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

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
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
        $this->commandBus->dispatch(
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
