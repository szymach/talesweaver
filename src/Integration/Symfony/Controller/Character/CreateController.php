<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Character\Create\Command;
use Talesweaver\Application\Character\Create\DTO;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Character\CreateType;
use Talesweaver\Integration\Symfony\Templating\Character\FormView;

class CreateController
{
    /**
     * @var FormView
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
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Scene $scene)
    {
        $form = $this->formFactory->create(CreateType::class, new DTO($scene), [
            'action' => $this->router->generate('character_new', ['id' => $scene->getId()]),
            'sceneId' => $scene->getId()
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($scene, $form->getData());
        }

        return $this->templating->createView($form, 'character.header.new');
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): Response
    {
        $description = $dto->getName();
        $avatar = $dto->getAvatar();
        $this->commandBus->handle(new Command(
            $scene,
            Uuid::uuid4(),
            new ShortText($dto->getName()),
            null !== $description ? new LongText($description) : null,
            null !== $avatar ? new File($avatar) : null
        ));

        return new JsonResponse(['success' => true]);
    }
}
