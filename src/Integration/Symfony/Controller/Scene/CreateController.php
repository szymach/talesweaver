<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Scene\Create\Command;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Type\Scene\CreateType;

class CreateController
{
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
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = $this->formFactory->create(CreateType::class, new DTO());
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($form->getData());
        }

        /* @var $chapter Chapter */
        $chapter = $form->get('chapter')->getData();
        return $this->responseFactory->fromTemplate(
            'scene/createForm.html.twig',
            ['form' => $form->createView(), 'chapterId' => $chapter ? $chapter->getId(): null]
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
}
