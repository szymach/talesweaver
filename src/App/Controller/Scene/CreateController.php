<?php

declare(strict_types=1);

namespace App\Controller\Scene;

use App\Form\Scene\CreateType;
use App\Routing\RedirectToEdit;
use App\Templating\SimpleFormView;
use Domain\Entity\Chapter;
use Domain\Scene\Create\Command;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

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
