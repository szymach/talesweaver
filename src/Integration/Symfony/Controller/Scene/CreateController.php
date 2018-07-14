<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Scene\Create\Command;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;
use Talesweaver\Integration\Symfony\Routing\RedirectToEdit;
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
     * @var RedirectToEdit
     */
    private $redirector;

    public function __construct(
        SimpleFormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RedirectToEdit $redirector
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->redirector = $redirector;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(CreateType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $sceneId = Uuid::uuid4();
            $this->commandBus->handle(new Command($sceneId, $form->getData()));

            return $this->redirector->createResponse('scene_edit', $sceneId);
        }

        /* @var $chapter Chapter */
        $chapter = $form->get('chapter')->getData();
        return $this->templating->createView(
            $form,
            'scene/createForm.html.twig',
            ['chapterId' => $chapter ? $chapter->getId(): null]
        );
    }
}
