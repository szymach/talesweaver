<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Application\Scene\Edit\Command;
use Talesweaver\Application\Scene\Edit\DTO;
use Talesweaver\Domain\Scene;
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
        $dto = new DTO($scene);
        $form = $this->formFactory->create(EditType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($dto, $scene));

            return $this->response->create($request, $scene->getId());
        }

        return $this->templating->createView($request, $form, $scene);
    }
}
