<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Application\Scene\Edit\Command;
use Talesweaver\Application\Scene\Edit\DTO;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Scene\EditType;
use Talesweaver\Integration\Symfony\Routing\Scene\EditResponse;
use Talesweaver\Integration\Symfony\Templating\Scene\EditView;

class EditController
{
    /**
     * @var EditView
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
     * @var EditResponse
     */
    private $response;

    public function __construct(
        EditView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        EditResponse $response
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->response = $response;
    }

    public function __invoke(Request $request, Scene $scene)
    {
        $form = $this->formFactory->create(EditType::class, new DTO($scene), ['sceneId' => $scene->getId()]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($request, $scene, $form->getData());
        }

        return $this->templating->createView($request, $form, $scene);
    }

    private function processFormDataAndRedirect(Request $request, Scene $scene, DTO $dto): Response
    {
        $text = $dto->getText();
        $this->commandBus->handle(new Command(
            $scene,
            new ShortText($dto->getTitle()),
            null !== $text ? new LongText($text) : null,
            $dto->getChapter()
        ));

        return $this->response->create($request, $scene->getId());
    }
}
