<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Scene\Create\Command;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;
use Talesweaver\Integration\Symfony\Templating\SimpleFormView;

class CreateController
{
    /**
     * @var SimpleFormView
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
        SimpleFormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $form = $this->formFactory->create(CreateType::class, new DTO());
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($form->getData());
        }

        /* @var $chapter Chapter */
        $chapter = $form->get('chapter')->getData();
        return $this->templating->createView(
            $form,
            'scene/createForm.html.twig',
            ['chapterId' => $chapter ? $chapter->getId(): null]
        );
    }

    private function processFormDataAndRedirect(DTO $dto): Response
    {
        $id = Uuid::uuid4();
        $this->commandBus->handle(
            new Command($id, new ShortText($dto->getTitle()), $dto->getChapter())
        );

        return $this->responseFactory->redirectToRoute('scene_edit', $id);
    }
}
