<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Character\Edit\Command;
use Talesweaver\Application\Character\Edit\DTO;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Character\EditType;
use Talesweaver\Integration\Symfony\Templating\Character\FormView;

class EditController
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

    public function __invoke(Request $request, Character $character)
    {
        $form = $this->formFactory->create(EditType::class, new DTO($character), [
            'action' => $this->router->generate('character_edit', ['id' => $character->getId()]),
            'characterId' => $character->getId()
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($character, $form->getData());
        }

        return $this->templating->createView($form, 'character.header.edit');
    }

    private function processFormDataAndRedirect(Character $character, DTO $dto): Response
    {
        $description = $dto->getName();
        $avatar = $dto->getAvatar();
        $this->commandBus->handle(new Command(
            $character,
            new ShortText($dto->getName()),
            null !== $description ? new LongText($description) : null,
            null !== $avatar ? new File($avatar) : null
        ));

        return new JsonResponse(['success' => true]);
    }
}
