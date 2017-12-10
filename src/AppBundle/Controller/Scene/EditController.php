<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\EditType;
use AppBundle\Routing\Scene\EditResponse;
use AppBundle\Templating\Scene\EditView;
use Domain\Scene\Edit\Command;
use Domain\Scene\Edit\DTO;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $dto = new DTO($scene);
        $form = $this->formFactory->create(EditType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($dto, $scene));

            return $this->response->create($request, $scene->getId());
        }

        return $this->templating->createView($request, $form, $scene);
    }
}
