<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Form\Scene\NewType;
use AppBundle\Routing\RedirectToEdit;
use AppBundle\Scene\Create\Command;
use AppBundle\Scene\Create\DTO;
use AppBundle\Templating\SimpleFormView;
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
        $form = $this->formFactory->create(NewType::class, new DTO());
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $sceneId = Uuid::uuid4();
            $this->commandBus->handle(new Command($sceneId, $form->getData()));

            return $this->redirector->createResponse('app_scene_edit', $sceneId);
        }

        return $this->templating->createView($form, 'scene/createForm.html.twig');
    }
}
